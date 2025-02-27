<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventoryWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $lowStockLevel = Product::where('stock', 0)
            ->orWhereRaw('stock <= min_stock')
            ->orWhereRaw('stock > min_stock AND stock <= (min_stock + 10)')
            ->count();

        return [
            Stat::make('Total Inventory', Product::count())
                ->icon('heroicon-m-shopping-bag'),
            Stat::make('Low Inventory', $lowStockLevel)
                ->icon('heroicon-m-exclamation-circle'),
            Stat::make('Out of Stock', Product::where('stock', 0)->count())
                ->icon('heroicon-m-exclamation-triangle'),
        ];
    }
}
