<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Level>
 */
class LevelFactory extends Factory
{
    protected $model = Level::class;

    public function definition(): array
    {
        return [
            'game_id' => Game::factory(),
            'order' => fake()->numberBetween(1, 20),
            'instruction' => fake()->sentence(),
            'initial_code' => '',
            'answer_key' => fake()->word(),
            'max_score' => 100,
        ];
    }
}
