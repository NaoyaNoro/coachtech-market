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
                'good'=>'3',
                'comment'=>'2',
                'description'=> 'スタイリッシュなデザインのメンズ腕時計',
                'image'=>'Armani+Mens+Clock.jpg',
                'category_id'=>'5',
                'status_id'=>'1',
            ],
            [
                'name' => 'HDD',
                'brand' => 'NEC',
                'price' => '5000',
                'good' => '3',
                'comment' => '2',
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'HDD+Hard+Disk.jpg',
                'category_id' => '2',
                'status_id' => '2',
            ],
        ];
        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }
    }
}
