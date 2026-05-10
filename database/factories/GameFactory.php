<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        $title = fake()->unique()->words(2, true);

        return [
            'slug' => \Illuminate\Support\Str::slug($title),
            'title' => ucwords($title),
            'description' => fake()->sentence(),
            'thumbnail' => null,
        ];
    }
}
