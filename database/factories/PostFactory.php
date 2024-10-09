<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'content' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['draft', 'pending', 'published']),
            'published_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'meta_title' => $this->faker->sentence(),
            'meta_description' => $this->faker->sentence(),
            'keywords' => $this->faker->words(3, true),
            'created_by' => 2,
        ];
    }
}
