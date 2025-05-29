<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueMonthLineChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue per Month';

    protected function getData(): array
    {
        $data = DB::table('transaction')
            ->selectRaw("DATE_FORMAT(invoice_date, '%b') as month, SUM(total_amount) as revenue")
            ->where('status', 'PAID')
            ->groupBy('month')
            ->orderByRaw("STR_TO_DATE(month, '%b')") // biar urut dari Jan–Dec
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data->pluck('revenue'),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => '#3B82F6',
                    'fill' => false,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->pluck('month'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): ?array
    {
        return [
            'scales' => [
                'y' => ['beginAtZero' => true],
            ],
        ];
    }
}
