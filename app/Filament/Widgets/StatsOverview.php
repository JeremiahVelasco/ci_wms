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
        $lowStockLevel = Product::where('stock', 0)
            ->orWhereRaw('stock <= min_stock')
            ->orWhereRaw('stock > min_stock AND stock <= (min_stock + 5)')
            ->count();

        return [
            Stat::make('Total Inventory', Product::count()),
            Stat::make('Low Inventory', $lowStockLevel)
                ->icon('heroicon-m-exclamation-circle'),
            Stat::make('Out of Stock', Product::where('stock', 0)->count())
                ->icon('heroicon-m-exclamation-triangle'),
            Stat::make('Total Transactions', Transaction::count()),
            Stat::make('Total Incoming Transactions', Transaction::where('status', 1)->count()),
            Stat::make('Total Outgoing Transactions', Transaction::where('status', 2)->count()),
        ];
    }
}
