<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
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
            'postal_code' => sprintf('%03d-%04d', rand(100, 999), rand(1000, 9999)),
            'address' => $this->faker->address(),
            'building' => null,
            'name' => $this->faker->name(),
        ];
    }
}
