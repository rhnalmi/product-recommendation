<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReturnsSeeder extends Seeder
{
    public function run()
    {
        $salesOrders = DB::table('sales_orders')->get();
        $products = DB::table('master_part')->pluck('part_number')->toArray();

        $datePrefix = Carbon::now()->format('Ymd');
        $counter = 1;

        foreach ($salesOrders as $salesOrder) {
            if (rand(0, 1) === 0) continue;

            $productId = $products[array_rand($products)];

            $returnId = 'RTN-' . $datePrefix . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $counter++;

            DB::table('product_returns')->insert([
                'return_id' => $returnId,
                'sales_order_id' => $salesOrder->sales_order_id,
                'part_number' => $productId,
                'quantity' => rand(1, 5),
                'return_date' => Carbon::now()->subDays(rand(1, 30))->toDateString(),
                'reason' => collect([
                    'Wrong item delivered',
                    'Item defective on arrival',
                    'Customer changed mind',
                    'Packaging damaged',
                    'Product expired'
                ])->random(),
                'condition' => rand(0, 1) ? 'GOOD' : 'DAMAGED',
            ]);
        }
    }
}
