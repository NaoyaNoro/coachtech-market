<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Category_ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrays=[
            [
                'product_id'=>1,
                'category_id'=>1
            ],
            [
                'product_id' => 1,
                'category_id' => 5
            ],
            [
                'product_id' => 1,
                'category_id' => 2
            ],
            [
                'product_id' => 3,
                'category_id' => 2
            ]
        ];
        foreach($arrays as $array){
            DB::table('category_product')->insert($array);
        }
    }
}
