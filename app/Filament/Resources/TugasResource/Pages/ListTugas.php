<?php

namespace App\Filament\Resources\TugasResource\Pages;

use App\Filament\Resources\TugasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListTugas extends ListRecords
{
    protected static string $resource = TugasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function canCreate(): bool
    {
        // Alternatif 1: Aman & simple
       /** @var \App\Models\User $user */
$user = Auth::user();
return Auth::check() && $user->isAdmin();


        // Alternatif 2: Jika tetap ingin pakai auth() helper
        // $user = auth()->user();
        // /** @var \App\Models\User|null $user */
        // return $user?->isAdmin() ?? false;
    }
}
