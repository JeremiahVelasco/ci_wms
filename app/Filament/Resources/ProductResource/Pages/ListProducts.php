<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\Widgets\InventoryWidget;
use App\Models\Product;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('New item')
                ->icon('heroicon-m-plus-circle')
                ->slideOver()
                ->modalWidth('xl')
                ->form([
                    TextInput::make('item')
                        ->label('Item name')
                        ->required(),
                    TextInput::make('brand'),
                    TextInput::make('description')
                        ->columnSpanFull(),
                    TextInput::make('stock')
                        ->numeric(),
                    TextInput::make('min_stock')
                        ->numeric(),
                ])
                ->action(function (array $data) {
                    DB::transaction(function () use ($data) {
                        $product = Product::create([
                            'item' => $data['item'],
                            'description' => $data['description'],
                            'brand' => $data['brand'],
                            'stock' => $data['stock'],
                            'min_stock' => $data['min_stock'],
                        ]);
                    });
                })
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            InventoryWidget::class
        ];
    }
}
