<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'user_id' => User::factory(),
            'title' => $this->faker->word(),
            'brand_name' => $this->faker->company(),
            'description' => $this->faker->sentence(10),
            'condition' => $this->faker->randomElement([
                ''
            ]),
            'price' => $this->faker->numberBetween(100, 10000),
        ];
    }
}
