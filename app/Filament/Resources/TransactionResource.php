<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'create_incoming_transaction',
            'create_outgoing_transaction',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',

        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('job_order'),
                Select::make('status')
                    ->required()
                    ->options([
                        1 => 'Incoming Inventory',
                        2 => 'Outgoing Inventory',
                    ]),
                Select::make('product_id')
                    ->label('Product')
                    ->options(Product::pluck('item', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('supplier_id')
                    ->label('Supplier')
                    ->options(Supplier::pluck('name', 'id'))
                    ->required(),
                Select::make('customer_id')
                    ->label('Customer')
                    ->options(Customer::pluck('name', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->badge()
                    ->color(fn($record) => match ($record->status) {
                        1 => 'success',
                        2 => 'warning',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        '1' => 'Incoming Inventory',
                        '2' => 'Outgoing Inventory',
                    }),
                TextColumn::make('product.item')
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->description(
                        fn(Model $record): ?string => $record->product?->brand ? 'Brand: ' . $record->product->brand : null
                    ),
                TextColumn::make('supplier.name')
                    ->searchable()
                    ->description(
                        fn(Model $record): ?string => $record->supplier?->contact ? 'Contact: ' . $record->supplier->contact : null
                    ),
                TextColumn::make('customer.name')
                    ->searchable(),
                TextColumn::make('job_order')
                    ->badge(),
                TextColumn::make('created_at')
                    ->date()
                    ->label('Transaction date')
                    ->description(
                        fn(Model $record): string =>
                        Carbon::parse($record->created_at)->diffForHumans()
                    ),
            ])
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(15)
            ->paginated([15, 25, 50, 100])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }

                        return $indicators;
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hiddenLabel(),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
