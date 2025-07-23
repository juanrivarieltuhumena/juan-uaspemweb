<?php

namespace App\Filament\Widgets;

use App\Models\Tugas;
use App\Models\TugasSelesai;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class StatusTugasChart extends ChartWidget
{
    protected static ?string $heading = 'Status Tugas: Selesai vs Belum';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $totalTugas = Tugas::count();
        $selesai = TugasSelesai::count();
        $belum = max($totalTugas - $selesai, 0);

        return [
            'datasets' => [
                [
                    'label' => 'Status Tugas',
                    'data' => [$selesai, $belum],
                    'backgroundColor' => [
                        '#22c55e', // Hijau: Selesai
                        '#ef4444', // Merah: Belum
                    ],
                ],
            ],
            'labels' => ['Selesai', 'Belum Selesai'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    public static function canView(): bool
    {
        return Auth::user()?->role === 'admin';
    }
}
