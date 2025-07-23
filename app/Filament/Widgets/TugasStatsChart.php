<?php

namespace App\Filament\Widgets;

use App\Models\Tugas;
use Filament\Widgets\ChartWidget;

class TugasStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Tugas';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Tugas Selesai',
                    'data' => Tugas::withCount('tugasSelesai')->pluck('tugas_selesai_count')->toArray(),
                    'backgroundColor' => '#10b981',
                ],
            ],
            'labels' => Tugas::pluck('judul')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
