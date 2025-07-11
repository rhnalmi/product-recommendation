<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubPart;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\DemandForecast;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ForecastDemand extends Command
{
    protected $signature = 'app:forecast-demand';
    protected $description = 'Analyze historical sales data from the Sales module to forecast future demand.';

    public function handle()
    {
        $this->info('Starting demand forecasting using actual sales data...');

        // Analisis data penjualan 3 bulan terakhir
        $monthsToAnalyze = 3;
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subMonths($monthsToAnalyze);

        // Ambil semua sub-part
        $subParts = SubPart::all();

        foreach ($subParts as $part) {
            // ---- LOGIKA BARU: MENGAMBIL DATA DARI SALES ORDERS ----
            // Hitung total kuantitas yang terjual dari sales_order_items
            // untuk pesanan yang statusnya 'confirmed' atau 'delivered'.
            $totalSales = SalesOrderItem::query()
                ->join('sales_orders', 'sales_order_items.sales_order_id', '=', 'sales_orders.sales_order_id')
                ->where('sales_order_items.part_number', $part->sub_part_number)
                ->whereIn('sales_orders.status', ['confirmed', 'delivered']) // Filter status pesanan
                ->whereBetween('sales_orders.order_date', [$startDate, $endDate]) // Filter berdasarkan tanggal pesanan
                ->sum('sales_order_items.quantity');
            // ----------------------------------------------------

            // Rata-rata penjualan bulanan
            $averageMonthlySales = $totalSales > 0 ? $totalSales / $monthsToAnalyze : 0;

            // Ambil stok saat ini
            $currentStock = Inventory::where('product_id', $part->sub_part_number)->sum('quantity_available');

            // Logika rekomendasi stok (misal, butuh stok untuk 1.5 bulan ke depan)
            $safetyFactor = 1.5;
            $recommendedStock = ceil($averageMonthlySales * $safetyFactor);

            // Simpan atau perbarui hasil prediksi
            DemandForecast::updateOrCreate(
                ['sub_part_number' => $part->sub_part_number],
                [
                    'sub_part_name' => $part->sub_part_name,
                    'average_monthly_sales' => $averageMonthlySales,
                    'recommended_stock_level' => $recommendedStock,
                    'current_stock' => $currentStock,
                    'forecast_date' => Carbon::now()
                ]
            );

            $this->line("Processed: {$part->sub_part_name} - Avg Sales: " . number_format($averageMonthlySales, 2));
        }

        $this->info('Demand forecasting based on sales data completed successfully!');
    }
}