<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LowStockInventory;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?int $navigationSort = -2;

    public function getHeaderWidgets(): array
    {
        return [
            StatsOverview::make(),
            LowStockInventory::make(),
        ];
    }
}
