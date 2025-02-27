<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Inventory', Product::count()),
            Stat::make('Total Incoming Transactions', Transaction::where('status', 1)->count()),
            Stat::make('Total Outgoing Transactions', Transaction::where('status', 2)->count()),
        ];
    }
}
