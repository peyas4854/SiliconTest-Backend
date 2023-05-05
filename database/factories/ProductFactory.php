<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'        => $this->faker->text(12),
            'image'       => "{$this->faker->image('public/storage/products',640,480, null, false)}",
            'description' => $this->faker->text(150),
            'price'       => $this->faker->randomFloat(3, 10, 100),
            'unit'=>'lorem ipsam',
            'stock'=>100,
            'created_by'  => 1,
        ];
    }
}
