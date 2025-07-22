<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TugasResource\Pages;
use App\Models\Tugas;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TugasResource extends Resource
{
    protected static ?string $model = Tugas::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Form untuk create/edit Tugas.
     * Sudah ditambahkan validasi agar data aman dari XSS/input kosong.
     */
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

    /**
     * Tampilan kolom di tabel tugas.
     */
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('judul')->label('Judul'),
            Tables\Columns\TextColumn::make('deskripsi')->label('Deskripsi'),
            Tables\Columns\TextColumn::make('deadline')->label('Deadline'),
            Tables\Columns\TextColumn::make('mahasiswa.name')->label('Mahasiswa'), // relasi ke tabel users
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

    /**
     * Batasi query berdasarkan role user yang login.
     * Admin melihat semua data. Mahasiswa hanya lihat datanya sendiri.
     */
    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Tambahan proteksi jika user belum login
        if (!$user) {
            return parent::getEloquentQuery()->whereRaw('0 = 1');
        }

        // Jika admin, tampilkan semua tugas
        if ($user->isAdmin()) {
            return parent::getEloquentQuery();
        }

        // Jika mahasiswa, hanya tampilkan tugas miliknya
        return parent::getEloquentQuery()->where('mahasiswa_id', $user->id);
    }
}
