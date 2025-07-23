<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';


    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }
}
