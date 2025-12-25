<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\ItemImage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemImage>
 */
class ItemImageFactory extends Factory
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
            'item_id' => Item::factory(),
            'image_path' => 'test_img.png',
        ];
    }
}
