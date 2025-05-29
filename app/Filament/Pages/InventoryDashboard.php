<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\RevenueLineChart;
use App\Filament\Widgets\DashboardOverview;
use App\Filament\Widgets\InventoryOverview;
use App\Filament\Widgets\SalesChart;
use App\Filament\Widgets\StatsOverview;

class InventoryDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Inventory'; 

    protected static string $view = 'filament.pages.inventory-dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            InventoryOverview::class,
            RevenueLineChart::class,
            SalesChart::class,
            StatsOverview::class, 
            ];
    } 
}
 