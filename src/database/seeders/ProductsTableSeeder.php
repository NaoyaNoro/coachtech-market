<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products=[
            [
                'name'=>'腕時計',
                'brand'=>'Armani',
                'price'=>'15000',
                'description'=> 'スタイリッシュなデザインのメンズ腕時計',
                'image'=>'Armani+Mens+Clock.jpg',
                'status'=> 'やや傷や汚れあり',
                'color'=>'黒'
            ],
            [
                'name' => 'HDD',
                'brand' => 'NEC',
                'price' => '5000',
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'HDD+Hard+Disk.jpg',
                'status' => '良好',
                'color'=>''
            ],
        ];
        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }
    }
}
