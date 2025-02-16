<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'ユーザー1',
                'email'=> 'user1@sample.com',
                'password' => Hash::make('user10000'),
            ],
            [
                'name' => 'ユーザー2',
                'email' => 'user2@sample.com',
                'password' => Hash::make('user20000'),
            ],
            [
                'name' => 'ユーザー3',
                'email' => 'user3@sample.com',
                'password' => Hash::make('user30000'),
            ],
        ];
        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}
