<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $comments = [
            'まとめ買いは可能でしょうか',
            '値下げしました！',
            '傷の程度を教えてください',
        ];

        return [
            //
            'comment' => $this->faker->randomElement($comments),
        ];
    }
}
