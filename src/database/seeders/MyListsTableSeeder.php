<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MyListsTableSeeder extends Seeder
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
                'product_id' => 2
            ],
            [
                'user_id' => 2,
                'product_id' => 1
            ],
        ];
        foreach ($mylists as $mylist) {
            DB::table('mylists')->insert($mylist);
        }
    }
}
