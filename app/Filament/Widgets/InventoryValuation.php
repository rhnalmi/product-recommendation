<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class InventoryValuation extends BaseWidget
{
    protected static ?int $sort = 1; // Atur prioritasnya di dashboard

    protected function getStats(): array
    {
        $totalValue = Inventory::query()
            ->join('sub_parts', 'inventory.product_id', '=', 'sub_parts.sub_part_number')
            ->sum(DB::raw('inventory.quantity_available * sub_parts.price'));

        $totalItems = Inventory::sum('quantity_available');
        $uniqueSKUs = Inventory::where('quantity_available', '>', 0)->count();

        return [
            Stat::make('Total Nilai Inventaris', 'Rp ' . number_format($totalValue, 2))
                ->description('Nilai total dari semua stok yang tersedia')
                ->color('success'),
            Stat::make('Total Item di Gudang', number_format($totalItems))
                ->description('Jumlah semua unit barang')
                ->color('primary'),
            Stat::make('Jumlah SKU Aktif', $uniqueSKUs)
                ->description('Jumlah jenis produk yang memiliki stok')
                ->color('info'),
        ];
    }
}