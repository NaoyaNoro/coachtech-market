<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            ProductsTableSeeder::class,
            MyListsTableSeeder::class,
            Category_ProductTableSeeder::class,
            CommentsTableSeeder::class,
            ProfilesTableSeeder::class,
            TransactionsTableSeeder::class,
            ChatsTableSeeder::class,
            SellTableSeeder::class
        ]);
    }
}
