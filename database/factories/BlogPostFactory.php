<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->unique()->sentence;

        return [
            'user_id' => mt_rand(1, 10),
            'slug' => strtolower(str_replace(' ', '-', $title)),
            'title' => $title,
            'content' => fake()->text,
            'created_at' => now(),
        ];
    }
}
