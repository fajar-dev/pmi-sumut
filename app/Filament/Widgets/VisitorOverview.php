<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class VisitorOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Visitor::whereDate('created_at', Carbon::today())->count();
        $thisWeek = Visitor::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();
        $thisMonth = Visitor::whereMonth('created_at', Carbon::now()->month)->count();

        return [
            Stat::make('Today', $today)->description('Visitors today'),
            Stat::make('This Week', $thisWeek)->description('Visitors this week'),
            Stat::make('This Month', $thisMonth)->description('Visitors this month'),
        ];
    }
}
