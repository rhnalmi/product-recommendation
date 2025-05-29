<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SalesDashboardKpi extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Revenue (from paid invoices)
        $totalRevenue = DB::table('transaction')
            ->where('status', 'PAID')
            ->sum('total_amount');

        // Total Sales Orders (CONFIRMED & DELIVERED)
        $totalSalesOrders = DB::table('sales_orders')
            ->whereIn('status', ['CONFIRMED', 'DELIVERED'])
            ->count();

        // Conversion Rate Quotation → SO
        $totalQuotations = DB::table('sales_orders')
            ->where('status', 'PENDING')
            ->count();

        $convertedToSO = DB::table('sales_orders')
            ->whereIn('status', ['CONFIRMED', 'DELIVERED'])
            ->whereIn('id', function ($query) {
                $query->select('sales_order_id')->from('delivery_orders');
            })
            ->count();

        $conversionRate = $totalQuotations > 0
            ? round(($convertedToSO / $totalQuotations) * 100, 2)
            : 0;

        // Outstanding Invoices (unpaid)
        $outstandingInvoices = DB::table('transaction')
            ->where('status', 'unpaid')
            ->count();

        // Total Returns (jumlah return + nominal refund)
        $totalReturnsQty = DB::table('product_returns')->sum('quantity');
        $totalRefundAmount = DB::table('credit_memos')->sum('amount');

        // Average Order Value
        $avgOrderValue = $totalSalesOrders > 0
            ? round($totalRevenue / $totalSalesOrders, 2)
            : 0;

        return [
            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('Total dari invoice yang sudah dibayar')
                ->color('success'),

            Stat::make('Total Sales Orders', $totalSalesOrders)
                ->description('Status Confirmed & Delivered')
                ->color('primary'),

            Stat::make('Conversion Rate', $conversionRate . '%')
                ->description('Quotation → SO')
                ->color('info'),

            Stat::make('Outstanding Invoices', $outstandingInvoices)
                ->description('Invoice belum dibayar')
                ->color('danger'),

            Stat::make('Total Returns', $totalReturnsQty . ' pcs / $' . number_format($totalRefundAmount, 2))
                ->description('Qty dikembalikan & Credit Memo')
                ->color('warning'),

            Stat::make('Avg Order Value', '$' . number_format($avgOrderValue, 2))
                ->description('Revenue / Total SO')
                ->color('gray'),
        ];
    }
}
