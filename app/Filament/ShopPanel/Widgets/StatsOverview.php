<?php

namespace App\Filament\ShopPanel\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
      

      

      

        return [
            Stat::make('Total Shops', 1)
                ->description('Shops owned by you')
                ->color('success'),


        ];
    }

    protected function getColumns(): int
    {
        return 2; // Display 3 stats per row for a balanced layout
    }
}
