<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactions = [
            [
                'product_id' => 1,
                'buyer_id'=>2,
                'seller_id'=>1
            ]
        ];
        foreach ($transactions as $transaction) {
            DB::table('transactions')->insert($transaction);
        }
    }
}
