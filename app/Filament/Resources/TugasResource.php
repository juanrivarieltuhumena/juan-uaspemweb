<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TugasResource\Pages;
use App\Models\Tugas;
use App\Models\User;
use App\Models\TugasSelesai;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action;
use Spatie\SimpleExcel\SimpleExcelWriter;

class TugasResource extends Resource
{
    protected static ?string $model = Tugas::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('judul')
                ->label('Judul')
                ->required()
                ->string()
                ->maxLength(255),

            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->nullable()
                ->maxLength(1000),

            Forms\Components\DatePicker::make('deadline')
                ->label('Deadline')
                ->required(),

            Forms\Components\Select::make('mahasiswa_id')
                ->label('Mahasiswa')
                ->searchable()
                ->preload()
                ->required()
                ->options(function () {
                    return User::where('role', 'mahasiswa')->pluck('name', 'id');
                }),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->label('Judul'),
                Tables\Columns\TextColumn::make('deskripsi')->label('Deskripsi'),
                Tables\Columns\TextColumn::make('deadline')->label('Deadline'),
                Tables\Columns\TextColumn::make('mahasiswa.name')->label('Mahasiswa'),
            ])
            ->actions([
                Action::make('tandai_selesai')
                    ->label('Tandai Selesai')
                    ->action(function (Tugas $record) {
                        $user = Auth::user();
                        if (!$user || $user->role !== 'mahasiswa') return;

                        $sudah = TugasSelesai::where('tugas_id', $record->id)
                            ->where('user_id', $user->id)
                            ->exists();

                        if (!$sudah) {
                            TugasSelesai::create([
                                'tugas_id' => $record->id,
                                'user_id' => $user->id,
                            ]);
                        }
                    })
                    ->visible(function (Tugas $record) {
                        $user = Auth::user();
                        if (!$user || $user->role !== 'mahasiswa') return false;

                        return !TugasSelesai::where('tugas_id', $record->id)
                            ->where('user_id', $user->id)
                            ->exists();
                    })
                    ->requiresConfirmation()
                    ->icon('heroicon-o-check-circle')
                    ->color('success'),
            ])
            ->headerActions([
                Action::make('Export CSV')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $filePath = storage_path('app/public/tugas-export.csv');

                        SimpleExcelWriter::create($filePath)
                            ->addRows(
                                Tugas::with('mahasiswa')->get()->map(function ($tugas) {
                                    return [
                                        'Judul' => $tugas->judul,
                                        'Deskripsi' => $tugas->deskripsi,
                                        'Deadline' => $tugas->deadline,
                                        'Mahasiswa' => $tugas->mahasiswa->name ?? '-',
                                    ];
                                })
                            );

                        return response()->download($filePath)->deleteFileAfterSend();
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->visible(function () {
                        /** @var \App\Models\User $user */
                        $user = Auth::user();
                        return $user && $user->isAdmin();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTugas::route('/'),
            'create' => Pages\CreateTugas::route('/create'),
            'edit' => Pages\EditTugas::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return parent::getEloquentQuery()->whereRaw('0 = 1');
        }

        if ($user->isAdmin()) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()->whereHas('mahasiswa', function ($query) use ($user) {
            $query->where('id', $user->id);
        });
    }
}
