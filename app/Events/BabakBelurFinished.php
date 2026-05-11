<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BabakBelurFinished implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $winner;
    public $finalRankings;

    public function __construct(Room $room, array $winner, array $finalRankings)
    {
        $this->room = $room;
        $this->winner = $winner;
        $this->finalRankings = $finalRankings;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('room.' . $this->room->code),
        ];
    }

    public function broadcastAs(): string
    {
        return 'babakbelur.finished';
    }

    public function broadcastWith(): array
    {
        return [
            'room_code' => $this->room->code,
            'winner' => $this->winner,
            'final_rankings' => $this->finalRankings,
            'total_stages' => $this->room->total_stages,
        ];
    }
}
