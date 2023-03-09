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
            'description' => fake()->sentence,
            'h1' => $title,
            'content' => fake()->text,
            'preview_text' => fake()->sentence,
            'preview_image' => 'https://source.unsplash.com/random/600x600',
            'image' => 'https://source.unsplash.com/random/600x600',
            'created_at' => now(),
        ];
    }
}
