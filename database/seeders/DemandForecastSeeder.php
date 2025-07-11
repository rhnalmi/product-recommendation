<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubPart;
use App\Models\Inventory;
use App\Models\DemandForecast;
use Carbon\Carbon;

class DemandForecastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama agar tidak duplikat setiap kali seeder dijalankan
        DemandForecast::truncate();

        // Ambil semua sub-part yang ada di database Anda
        $subParts = SubPart::all();

        // Loop untuk setiap sub-part untuk membuat data prediksi
        foreach ($subParts as $part) {
            
            // Ambil data stok saat ini dari tabel inventory
            $currentStock = Inventory::where('product_id', $part->sub_part_number)
                                     ->value('quantity_available') ?? 0;

            // ---- Logika untuk Membuat Data Palsu yang Realistis ----
            // Kita buat angka penjualan bulanan palsu secara acak
            $simulatedMonthlySales = rand(5, 150); // Anggap penjualan antara 5 s/d 150 unit/bulan
            
            // Faktor keamanan, biasanya antara 1.2 s/d 2.0
            $safetyFactor = 1.5; 
            
            // Hitung rekomendasi stok berdasarkan penjualan & faktor keamanan
            $recommendedStock = ceil($simulatedMonthlySales * $safetyFactor);
            // --------------------------------------------------------

            // Simpan data prediksi palsu ini ke dalam tabel demand_forecasts
            DemandForecast::create([
                'sub_part_number' => $part->sub_part_number,
                'sub_part_name' => $part->sub_part_name,
                'average_monthly_sales' => $simulatedMonthlySales,
                'current_stock' => $currentStock,
                'recommended_stock_level' => $recommendedStock,
                'forecast_date' => Carbon::now(), // Gunakan tanggal hari ini
            ]);
        }
    }
}