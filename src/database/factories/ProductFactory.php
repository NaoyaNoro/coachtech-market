<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->word(),
            'brand'=>$this->faker->company(),
            'price'=>$this->faker->randomNumber(5),
            'description'=>$this->faker->sentence(),
            'image'
            => 'test-image-' . $this->faker->unique()->numberBetween(1, 1000) . '.jpg',
            'status'=>'良好',
            'color'=>$this->faker->safeColorName(),
            'created_at'=>now(),
            'updated_at'=>now()
        ];
    }
}
