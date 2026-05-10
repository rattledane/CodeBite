<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\RoomParticipant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoomParticipant>
 */
class RoomParticipantFactory extends Factory
{
    protected $model = RoomParticipant::class;

    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'user_id' => User::factory(),
            'score' => 0,
            'current_level' => 1,
            'rank' => null,
            'is_finished' => false,
            'finished_at' => null,
        ];
    }
}
