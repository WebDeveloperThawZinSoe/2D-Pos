<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;


class StatsOverview extends BaseWidget
{
    protected static bool $isLazy = false;



    protected function getStats(): array
    {
       
        return [
            // Stat::make('Shop', 1)
            // ->description('Shop Count On Our Platform')
            // ->color('success'),
        ];
    }
}
