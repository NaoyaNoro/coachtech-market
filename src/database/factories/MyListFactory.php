<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MyList;
use App\Models\User;
use App\Models\Product;

class MyListFactory extends Factory
{
    protected $model = MyList::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
