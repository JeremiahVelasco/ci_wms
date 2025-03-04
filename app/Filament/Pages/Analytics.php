<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\IncomingTransactionsPerMonth;
use App\Filament\Widgets\OutgoingTransactionsPerMonth;
use App\Filament\Widgets\TransactionWidget;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\Select;
use Filament\Tables\Enums\FiltersLayout;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class Analytics extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $title = 'Reports';
    protected static string $view = 'filament.pages.analytics';

    public string $activeTab = 'transactions';

    public function getHeaderWidgets(): array
    {
        return [
            TransactionWidget::make()
        ];
    }

    public function mount(): void
    {
        $this->activeTab = request()->query('tab', 'transactions');
    }

    public function table(Table $table): Table
    {
        $query = $this->activeTab === 'transactions'
            ? Transaction::query()
            : Product::query();

        return $table
            ->query($query)
            ->columns([

                // Transaction columns
                TextColumn::make('status')
                    ->visible(fn() => $this->activeTab === 'transactions')
                    ->formatStateUsing(fn($state) => match ($state) {
                        1 => 'Incoming',
                        2 => 'Outgoing',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        1 => 'success',
                        2 => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('job_order')
                    ->visible(fn() => $this->activeTab === 'transactions'),
                TextColumn::make('product.item')
                    ->visible(fn() => $this->activeTab === 'transactions'),
                TextColumn::make('amount')
                    ->label('Quantity')
                    ->visible(fn() => $this->activeTab === 'transactions'),
                TextColumn::make('created_at')
                    ->label('Transaction date')
                    ->date()
                    ->visible(fn() => $this->activeTab === 'transactions'),

                // Product columns
                TextColumn::make('item')
                    ->visible(fn() => $this->activeTab === 'products'),
                TextColumn::make('description')
                    ->visible(fn() => $this->activeTab === 'products'),
                TextColumn::make('stock')
                    ->visible(fn() => $this->activeTab === 'products'),
            ])
            ->filters([
                // Date filters
                Filter::make('today')
                    ->visible(fn() => $this->activeTab === 'transactions')
                    ->label('Today')
                    ->query(fn(Builder $query): Builder => $query->whereDate('created_at', Carbon::today())),

                Filter::make('this_week')
                    ->visible(fn() => $this->activeTab === 'transactions')
                    ->label('This Week')
                    ->query(fn(Builder $query): Builder => $query->whereBetween(
                        'created_at',
                        [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                    )),

                Filter::make('this_month')
                    ->visible(fn() => $this->activeTab === 'transactions')
                    ->label('This Month')
                    ->query(fn(Builder $query): Builder => $query->whereMonth(
                        'created_at',
                        Carbon::now()->month
                    )->whereYear('created_at', Carbon::now()->year)),
            ], layout: FiltersLayout::AboveContent)
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make('table')->fromTable(),
                    ExcelExport::make()->withFilename(date('Y-m-d') . ' - Report'),
                    ExcelExport::make()->fromTable()->except(['item', 'description', 'stock']),
                    ExcelExport::make()
                        ->fromTable()
                        ->modifyQueryUsing(function ($query) use ($table) {
                            // Start with the exportable condition
                            $query->where('exportable', true);

                            // Get active filters
                            $activeFilters = $table->getActiveFilters();

                            // Apply date filters based on active preset filters
                            if (in_array('today', $activeFilters)) {
                                $query->whereDate('created_at', Carbon::today());
                            } elseif (in_array('this_week', $activeFilters)) {
                                $query->whereBetween('created_at', [
                                    Carbon::now()->startOfWeek(),
                                    Carbon::now()->endOfWeek()
                                ]);
                            } elseif (in_array('this_month', $activeFilters)) {
                                $query->whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year);
                            }

                            return $query;
                        })
                ])
            ])
            ->actions([])
            ->bulkActions([
                // ...
            ]);
    }

    // Method to handle tab switching
    public function switchTab(string $tabId): void
    {
        $this->activeTab = $tabId;
    }
}
