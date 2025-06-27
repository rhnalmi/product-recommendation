<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\MasterPart;
use App\Models\SubPart;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class StockByCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Stok berdasarkan Kategori Master Part';
    protected static ?int $sort = 4;
    protected static ?string $pollingInterval = '30s';
    protected int | string | array $columnSpan = 'full'; // Atau sesuaikan

    // Asumsi MasterPart memiliki kolom 'category_name' atau relasi ke tabel kategori
    // Jika tidak ada kategori, Anda bisa mengganti ini menjadi 'Top N Stocked Master Parts'

    protected function getData(): array
    {
        // Query ini SANGAT bergantung pada struktur Anda.
        // Asumsi: MasterPart punya 'category_name'
        //         SubPart punya 'part_number' (FK ke MasterPart) dan 'sub_part_number' (PK)
        //         Inventory punya 'sub_part_number' (FK ke SubPart) dan 'quantity'
        $data = MasterPart::query()
            ->join('sub_parts', 'master_part.part_number', '=', 'sub_parts.part_number')
            ->join('inventories', 'sub_parts.sub_part_number', '=', 'inventories.sub_part_number')
            ->select('master_part.category_name', DB::raw('SUM(inventories.quantity) as total_stock'))
            ->groupBy('master_part.category_name')
            ->orderBy('total_stock', 'desc')
            ->get();

        // Jika tidak ada 'category_name' di MasterPart, Anda mungkin perlu menyesuaikan.
        // Misalnya, jika 'category_name' ada di tabel terpisah yang berelasi dengan MasterPart:
        // $data = MasterPart::with('category') // Asumsi relasi 'category' di model MasterPart
        //     ->join(...) // join dengan sub_parts dan inventories
        //     ->select('categories.name as category_name', DB::raw('SUM(inventories.quantity) as total_stock'))
        //     ->groupBy('categories.name') // Group by nama kategori dari tabel relasi
        //     ->get();


        return [
            'datasets' => [
                [
                    'label' => 'Total Stok',
                    'data' => $data->map(fn ($item) => $item->total_stock)->all(),
                    'backgroundColor' => [ // Tambahkan warna sesuai jumlah kategori
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ],
                ],
            ],
            'labels' => $data->map(fn ($item) => $item->category_name ?? 'Tidak Berkategori')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // Atau 'doughnut', 'bar'
    }
}