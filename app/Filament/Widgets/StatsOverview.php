<?php

namespace App\Filament\Widgets;

use App\Models\Tugas;
use App\Models\User;
use App\Models\TugasSelesai; // Tambahkan ini
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends StatsOverviewWidget
{
    // Batasi hanya admin yang bisa melihat widget ini
    public static function canView(): bool
    {
        return Auth::user()?->role === 'admin';
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Mahasiswa', User::where('role', 'mahasiswa')->count())
                ->description('Jumlah akun mahasiswa')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Total Tugas', Tugas::count())
                ->description('Jumlah semua tugas')
                ->icon('heroicon-o-document')
                ->color('info'),

            Stat::make('Tugas Selesai', TugasSelesai::count())
                ->description('Tugas yang sudah dikerjakan mahasiswa')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
