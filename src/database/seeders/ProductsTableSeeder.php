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
                'status'=> '良好',
                'color'=>'黒'
            ],
            [
                'name' => 'HDD',
                'brand' => 'NEC',
                'price' => '5000',
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'HDD+Hard+Disk.jpg',
                'status' => '目立った傷や汚れなし',
                'color'=>''
            ],
            [
                'name' => '玉ねぎ3束',
                'brand' => '無農薬農場',
                'price' => '300',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'iLoveIMG+d.jpg',
                'status' => 'やや傷や汚れあり',
                'color' => '茶色'
            ],
            [
                'name' => '革靴',
                'brand' => 'Crockett&Jones',
                'price' => '4000',
                'description' => 'クラシックなデザインの革靴',
                'image' => 'Leather+Shoes+Product+Photo.jpg',
                'status' => '状態が悪い',
                'color' => '黒'
            ],
            [
                'name' => 'ノートPC',
                'brand' => 'Dell',
                'price' => '45000',
                'description' => '高性能なノートパソコン',
                'image' => 'Living+Room+Laptop.jpg',
                'status' => '良好',
                'color' => 'グレー'
            ],
            [
                'name' => 'マイク',
                'brand' => 'AKG',
                'price' => '8000',
                'description' => '高音質のレコーディング用マイク',
                'image' => 'Music+Mic+4632231.jpg',
                'status' => '目立った傷や汚れなし',
                'color' => '黒'
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand' => 'Hermes',
                'price' => '3500',
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'Purse+fashion+pocket.jpg',
                'status' => 'やや傷や汚れあり',
                'color' => '赤'
            ],
            [
                'name' => 'タンブラー',
                'brand' => 'スターバックス',
                'price' => '500',
                'description' => '使いやすいタンブラー',
                'image' => 'Tumbler+souvenir.jpg',
                'status' => '状態が悪い',
                'color' => '黒'
            ],
            [
                'name' => 'コーヒーミル',
                'brand' => 'Kalita',
                'price' => '4000',
                'description' => '手動のコーヒーミル',
                'image' => 'Waitress+with+Coffee+Grinder.jpg',
                'status' => '良好',
                'color' => '茶色'
            ],
            [
                'name' => 'メイクセット',
                'brand' => 'MAC',
                'price' => '2500',
                'description' => '便利なメイクアップセット',
                'image' => '外出メイクアップセット.jpg',
                'status' => '目立った傷や汚れなし',
                'color' => ''
            ],
        ];
        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }
    }
}
