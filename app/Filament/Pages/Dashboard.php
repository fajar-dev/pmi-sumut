<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\VisitorChart;
use App\Filament\Widgets\VisitorOverview;
use Filament\Pages\Dashboard as DashboardBase;

class Dashboard extends DashboardBase
{
    protected function getHeaderWidgets(): array
    {
        // Statistik count di atas
        return [
            VisitorOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        // Grafik di bawah
        return [
            VisitorChart::class,
        ];
    }
}
