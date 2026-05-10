<?php

namespace App\Events;

use App\Models\Room;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScoreUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $user;
    public $score;
    public $currentLevel;

    public function __construct(Room $room, User $user, int $score, int $currentLevel)
    {
        $this->room = $room;
        $this->user = $user;
        $this->score = $score;
        $this->currentLevel = $currentLevel;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('room.' . $this->room->code),
        ];
    }

    public function broadcastAs(): string
    {
        return 'score.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'username' => $this->user->name,
            'avatar' => $this->user->avatar,
            'score' => $this->score,
            'current_level' => $this->currentLevel,
        ];
    }
}
