<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class LowStockInventory extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        $query = Product::query()
            ->where(function ($query) {
                // Products with zero stock
                $query->where('stock', 0);
            })
            ->orWhere(function ($query) {
                // Products with stock below or equal to min_stock
                $query->whereRaw('stock <= min_stock');
            })
            ->orWhere(function ($query) {
                // Products with stock up to 10 units above min_stock
                $query->whereRaw('stock > min_stock')
                    ->whereRaw('stock <= (min_stock + 10)');
            });

        return $table
            ->query($query)
            ->striped()
            ->columns([
                TextColumn::make('item'),
                TextColumn::make('stock')
                    ->color('danger')
                    ->badge(),
            ])
            ->recordUrl(
                fn(Model $record): string => ProductResource::getUrl('edit', ['record' => $record])
            );
    }
}
