<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;

    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('room.' . $this->room->code),
        ];
    }

    public function broadcastAs(): string
    {
        return 'game.started';
    }

    public function broadcastWith(): array
    {
        $firstLevel = $this->room->game->levels()->orderBy('order')->first();

        return [
            'game_slug' => $this->room->game->slug,
            'first_level' => $firstLevel ? [
                'id' => $firstLevel->id,
                'order' => $firstLevel->order,
                'instruction' => $firstLevel->instruction,
                'initial_code' => $firstLevel->initial_code,
                'max_score' => $firstLevel->max_score,
            ] : null,
            'started_at' => $this->room->started_at->toISOString(),
        ];
    }
}
