<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Add Incoming Inventory')
                ->label('Add Incoming Inventory')
                ->icon('heroicon-m-arrow-down-on-square')
                ->color('success')
                ->slideOver()
                ->modalWidth('xl')
                ->form([
                    Select::make('product_id')
                        ->label('Product')
                        ->options(Product::pluck('item', 'id'))
                        ->searchable()
                        ->searchable()
                        ->required(),
                    Select::make('supplier_id')
                        ->label('Supplier')
                        ->options(Supplier::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Select::make('customer_id')
                        ->label('Customer')
                        ->options(Customer::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    TextInput::make('amount')
                        ->label('Quantity')
                        ->required()
                ])
                ->action(function (array $data) {
                    DB::transaction(function () use ($data) {
                        $transaction = Transaction::create([
                            'status' => 1,
                            'product_id' => $data['product_id'],
                            'supplier_id' => $data['supplier_id'],
                            'customer_id' => $data['customer_id'],
                            'actor' => Auth::user()->name
                        ]);

                        // ! For product increment
                        $product = Product::find($data['product_id']);
                        $product->increment('stock', $data['amount']);
                    });
                }),
            Action::make('Add Outgoing Inventory')
                ->label('Add Outgoing Inventory')
                ->icon('heroicon-m-arrow-up-on-square')
                ->color('warning')
                ->slideOver()
                ->modalWidth('xl')
                ->form([
                    TextInput::make('job_order')
                        ->label('Job Order #'),
                    Select::make('customer_id')
                        ->label('Customer')
                        ->options(Customer::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Select::make('product_id')
                        ->label('Product')
                        ->options(Product::pluck('item', 'id'))
                        ->searchable()
                        ->required(),
                    Select::make('supplier_id')
                        ->label('Supplier')
                        ->options(Supplier::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    TextInput::make('amount')
                        ->label('Quantity')
                        ->required()
                ])
                ->action(function (array $data) {
                    DB::transaction(function () use ($data) {
                        $transaction = Transaction::create([
                            'job_order' => $data['job_order'],
                            'status' => 2,
                            'product_id' => $data['product_id'],
                            'supplier_id' => $data['supplier_id'] ?? null,
                            'customer_id' => $data['customer_id'] ?? null,
                            'actor' => Auth::user()->name
                        ]);

                        // ! For product decrement
                        $product = Product::find($data['product_id']);
                        $product->decrement('stock', $data['amount']);
                    });
                }),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'Incoming Inventory' => Tab::make()
                ->icon('heroicon-m-arrow-down-on-square')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 1)),
            'Outgoing Inventory' => Tab::make()
                ->icon('heroicon-m-arrow-up-on-square')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 2)),
        ];
    }
}
