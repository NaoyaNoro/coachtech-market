<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SellTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sells = [
            [
                'user_id'=>1,
                'product_id'=>1
            ],
            [
                'user_id' => 1,
                'product_id' => 2
            ],
            [
                'user_id' => 1,
                'product_id' => 3
            ],
            [
                'user_id' => 1,
                'product_id' => 4
            ],
            [
                'user_id' => 1,
                'product_id' => 5
            ],
            [
                'user_id' => 2,
                'product_id' => 6
            ],
            [
                'user_id' => 2,
                'product_id' => 7
            ],
            [
                'user_id' => 2,
                'product_id' => 8
            ],
            [
                'user_id' => 2,
                'product_id' => 9
            ],
            [
                'user_id' => 2,
                'product_id' => 10
            ],
        ];
        foreach ($sells as $sell) {
            DB::table('sells')->insert($sell);
        }
    }
}
