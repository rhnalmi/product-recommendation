<?php // app/Filament/Widgets/TopStockedSubPartsChart.php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Inventory;
use App\Models\SubPart;
use Illuminate\Support\Facades\DB;

class TopStockedSubPartsChart extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Sub Part dengan Stok Terbanyak';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1; // Misal ingin setengah lebar

    protected function getData(): array
    {
        $topItems = Inventory::query()
            ->join('sub_parts', 'inventories.sub_part_number', '=', 'sub_parts.sub_part_number')
            ->select('sub_parts.sub_part_name', 'inventories.quantity')
            ->orderByDesc('inventories.quantity')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Kuantitas Stok',
                    'data' => $topItems->pluck('quantity')->all(),
                    'backgroundColor' => '#36A2EB',
                ],
            ],
            'labels' => $topItems->pluck('sub_part_name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}