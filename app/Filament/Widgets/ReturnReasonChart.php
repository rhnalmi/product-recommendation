<?php

namespace App\Filament\Widgets;

use App\Models\ProductReturn;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ReturnReasonChart extends ChartWidget
{
    protected static ?string $heading = 'Penyebab Retur Terbanyak';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $data = ProductReturn::query()
            ->select('reason', DB::raw('count(*) as total'))
            ->groupBy('reason')
            ->orderBy('total', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Retur',
                    'data' => $data->pluck('total')->all(),
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                ],
            ],
            'labels' => $data->pluck('reason')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}