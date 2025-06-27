<?php

namespace App\Filament\Widgets;

use App\Models\Inventory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StockAvailabilityWidget extends BaseWidget
{
    // Atur urutan widget ini di dashboard
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        // Mengambil nama tabel dari model untuk menghindari kesalahan
        $inventoryTable = (new Inventory())->getTable();

        // Menjalankan satu query untuk mendapatkan semua data SUM agar lebih efisien
        $stockTotals = DB::table($inventoryTable)
            ->selectRaw('SUM(quantity_available) as total_available, SUM(quantity_reserved) as total_reserved')
            ->first();

        // Mengambil nilai dari hasil query
        $availableStock = $stockTotals->total_available ?? 0;
        $reservedStock = $stockTotals->total_reserved ?? 0;
        
        // Menghitung stok yang benar-benar bebas
        $freeStock = $availableStock - $reservedStock;

        return [
            Stat::make('Stok Tersedia (On Hand)', number_format($availableStock))
                ->description('Total stok fisik yang tercatat di semua gudang.')
                ->color('primary'),

            Stat::make('Stok Dipesan (Reserved)', number_format($reservedStock))
                ->description('Stok yang sudah dialokasikan untuk sales order aktif.')
                ->color('warning'),

            Stat::make('Stok Bebas (Free Stock)', number_format($freeStock))
                ->description('Jumlah stok aman yang bisa dijanjikan ke pelanggan.')
                ->color('success'),
        ];
    }
}