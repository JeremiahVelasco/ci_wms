<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use App\Models\Supplier;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;

class ListSuppliers extends ListRecords
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('New supplier')
                ->icon('heroicon-m-plus-circle')
                ->slideOver()
                ->modalWidth('xl')
                ->form([
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('address'),
                    TextInput::make('contact')
                ])
                ->action(function (array $data) {
                    DB::transaction(function () use ($data) {
                        $supplier = Supplier::create([
                            'name' => $data['name'],
                            'address' => $data['address'],
                            'contact' => $data['contact'],
                        ]);
                    });
                })
        ];
    }
}
