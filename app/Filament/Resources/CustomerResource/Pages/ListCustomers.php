<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('New customer')
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
                        $customer = Customer::create([
                            'name' => $data['name'],
                            'address' => $data['address'],
                            'contact' => $data['contact'],
                        ]);
                    });
                })
        ];
    }
}
