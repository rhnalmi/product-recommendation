<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreditMemosSeeder extends Seeder
{
    public function run()
    {
        $returns = DB::table('product_returns')->get();
        $counter = 1;

        foreach ($returns as $return) {
            DB::table('credit_memos')->insert([
                'credit_memos_id' => 'CM-' . str_pad($counter, 5, '0', STR_PAD_LEFT),
                'return_id' => $return->return_id,
                'amount' => rand(50, 500) + rand(0, 99) / 100,
                'issued_date' => Carbon::parse($return->return_date)->addDays(rand(0, 7)),
                'status' => collect(['DRAFT', 'ISSUED', 'USED'])->random(),
            ]);
            $counter++;
        }
    }
}
