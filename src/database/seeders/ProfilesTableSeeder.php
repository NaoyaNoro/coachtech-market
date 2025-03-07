<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profiles = [
            [
                'user_id'=>1,
                'image'=>'default.jpg',
                'post_code'=> '160-0023',
                'address'=> '東京都新宿区西新宿1-2-3',
                'building'=> '新宿グランドタワー 15階'
            ],
            [
                'user_id' => 2,
                'image' => 'default.jpg',
                'post_code' => '530-0011',
                'address' => '大阪府大阪市北区大深町4-5-6',
                'building' => '梅田スカイレジデンス 802号室'
            ],
            [
                'user_id' => 3,
                'image' => 'default.jpg',
                'post_code' => '810-0801',
                'address' => '福岡県福岡市博多区中洲2-7-8',
                'building' => 'リバーサイド博多ビル 3階'
            ],
        ];
        foreach ($profiles as $profile) {
            DB::table('profiles')->insert($profile);
        }
    }
}
