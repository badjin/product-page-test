<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Product::class;

    public function definition()
    {
        $name = $this->faker->words(3, true);
        return [
            'name' => ucfirst($name),
            'description' => $this->faker->paragraph,
            'slug' => Str::slug($name),
            'price' => $this->faker->numberBetween(10000, 100000),
            'active' => true,
        ];
    }
}
