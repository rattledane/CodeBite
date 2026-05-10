<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(\Illuminate\Support\Str::random(6)),
            'host_id' => User::factory(),
            'game_id' => Game::factory(),
            'status' => 'waiting',
            'max_players' => 10,
            'started_at' => null,
            'finished_at' => null,
        ];
    }
}
