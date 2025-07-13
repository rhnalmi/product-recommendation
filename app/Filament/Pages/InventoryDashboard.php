<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AfterSalesKpiWidget;
use Filament\Pages\Page;
use App\Filament\Widgets\RevenueLineChart;
use App\Filament\Widgets\DashboardOverview;
use App\Filament\Widgets\FastMovingItemsChart;
use App\Filament\Widgets\InventoryOverview;
use App\Filament\Widgets\MostReturnedProductsChart;
use App\Filament\Widgets\NewProductReturnsTable;
use App\Filament\Widgets\PendingShipmentsTable;
use App\Filament\Widgets\ReturnReasonChart;
use App\Filament\Widgets\SalesChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\StockAvailabilityWidget;
use App\Filament\Widgets\TopReturningDealersChart;
use App\Filament\Widgets\DamagedStockReport;
use App\Filament\Widgets\Inventory\DeadStockTable;
use App\Filament\Widgets\InventoryValuation;
use App\Filament\Widgets\LowStockItemsTable;
use App\Filament\Widgets\Inventory\SlowMovingItemsChart;

class InventoryDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Inventory'; 

    protected static string $view = 'filament.pages.inventory-dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            AfterSalesKpiWidget::class,
            StockAvailabilityWidget::class,
            
            SlowMovingItemsChart::class,
            DeadStockTable::class,
            
            NewProductReturnsTable::class,
            ReturnReasonChart::class,
            MostReturnedProductsChart::class,
            //StatsOverview::class,
            FastMovingItemsChart::class, 

            // Widget baru ditambahkan di sini
            PendingShipmentsTable::class,
            TopReturningDealersChart::class,

            // InventoryValuation::class, // <-- TAMBAHKAN INI
            DamagedStockReport::class, // <-- TAMBAHKAN INI
            LowStockItemsTable::class, // <-- TAMBAHKAN INI
            ];
    } 
}
 