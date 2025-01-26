<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MyListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mylists = [
            [
                'user_id' => 1,
                'product_id' => 1
            ],
            [
                'user_id' => 1,
                'product_id' => 3
            ],
            [
                'user_id' => 19,
                'product_id' => 1
            ],
        ];
        foreach ($mylists as $mylist) {
            DB::table('mylists')->insert($mylist);
        }
    }
}
