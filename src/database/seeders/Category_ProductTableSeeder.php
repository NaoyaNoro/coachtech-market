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
                'product_id' => 2,
                'category_id' => 2
            ],
            [
                'product_id' => 3,
                'category_id' => 10
            ],
            [
                'product_id' => 3,
                'category_id' => 11
            ],
            [
                'product_id' => 4,
                'category_id' => 1
            ],
            [
                'product_id' => 4,
                'category_id' => 5
            ],
            [
                'product_id' => 5,
                'category_id' => 2
            ],
            [
                'product_id' => 5,
                'category_id' => 8
            ],
            [
                'product_id' => 6,
                'category_id' => 13
            ],
            [
                'product_id' => 7,
                'category_id' => 1
            ],
            [
                'product_id' => 7,
                'category_id' => 4
            ],
            [
                'product_id' => 8,
                'category_id' => 10
            ],
            [
                'product_id' => 9,
                'category_id' => 2
            ],
            [
                'product_id' => 9,
                'category_id' => 3
            ],
            [
                'product_id' => 9,
                'category_id' => 10
            ],
            [
                'product_id' => 10,
                'category_id' => 4
            ],
            [
                'product_id' => 10,
                'category_id' => 6
            ],
        ];
        foreach($arrays as $array){
            DB::table('category_products')->insert($array);
        }
    }
}
