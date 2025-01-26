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
            /*コメント中
            CategoriesTableSeeder::class,
            StatusTableSeeder::class,
            ProductsTableSeeder::class,
            MyListTableSeeder::class,
            Category_ProductTableSeeder::class
            */
            CommentsTableSeeder::class
        ]);
    }
}
