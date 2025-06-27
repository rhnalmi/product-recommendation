<?php

namespace App\Filament\Widgets;

use App\Models\ProductReturn;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MostReturnedProductsChart extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Produk Paling Sering Diretur';
    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $data = ProductReturn::query()
            ->join('sub_parts', 'product_returns.part_number', '=', 'sub_parts.sub_part_number')
            ->select('sub_parts.sub_part_name', DB::raw('SUM(product_returns.quantity) as total_returned'))
            ->groupBy('sub_parts.sub_part_name')
            ->orderBy('total_returned', 'desc')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Diretur',
                    'data' => $data->pluck('total_returned')->all(),
                    'backgroundColor' => '#FF6384',
                ],
            ],
            'labels' => $data->pluck('sub_part_name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}