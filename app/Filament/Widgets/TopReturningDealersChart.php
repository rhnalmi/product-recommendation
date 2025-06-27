<?php

namespace App\Filament\Widgets;

use App\Models\ProductReturn;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopReturningDealersChart extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Dealer dengan Retur Terbanyak';
    protected static ?int $sort = 8;

    protected function getData(): array
    {
        // Menggunakan Query Builder untuk melakukan join antar tabel
        $data = DB::table('product_returns')
            ->join('sales_orders', 'product_returns.sales_order_id', '=', 'sales_orders.sales_order_id')
            ->join('outlet_dealers', 'sales_orders.customer_id', '=', 'outlet_dealers.outlet_code')
            ->select('outlet_dealers.outlet_name', DB::raw('COUNT(product_returns.id) as total_returns'))
            ->groupBy('outlet_dealers.outlet_name')
            ->orderBy('total_returns', 'desc')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Retur',
                    'data' => $data->pluck('total_returns')->all(),
                    'backgroundColor' => '#F59E0B',
                ],
            ],
            'labels' => $data->pluck('outlet_name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}