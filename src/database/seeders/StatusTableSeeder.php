<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status')->insert([
            ['status' => '良好'],
            ['status' => '目立った傷や汚れなし'],
            ['status' => 'やや傷や汚れあり'],
            ['status' => '状態が悪い'],
        ]);
    }
}
