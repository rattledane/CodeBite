<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class RaceFinished implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $rankings;

    public function __construct(Room $room, Collection $rankings)
    {
        $this->room = $room;
        $this->rankings = $rankings;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('room.' . $this->room->code),
        ];
    }

    public function broadcastAs(): string
    {
        return 'race.finished';
    }

    public function broadcastWith(): array
    {
        return [
            'room_code' => $this->room->code,
            'rankings' => $this->rankings->map(function ($p) {
                return [
                    'rank' => $p->rank,
                    'username' => $p->user->name ?? 'Unknown',
                    'avatar' => $p->user->avatar,
                    'score' => $p->score,
                    'time' => $p->finished_at ? $p->finished_at->diffInSeconds($this->room->started_at) : null,
                ];
            })->values()->toArray(),
        ];
    }
}
