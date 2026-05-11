<?php

namespace App\Events;

use App\Models\Room;
use App\Models\Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StageStarting implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $stageNumber;
    public $game;
    public $allGames;

    public function __construct(Room $room, int $stageNumber, Game $game, array $allGames)
    {
        $this->room = $room;
        $this->stageNumber = $stageNumber;
        $this->game = $game;
        $this->allGames = $allGames;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('room.' . $this->room->code),
        ];
    }

    public function broadcastAs(): string
    {
        return 'stage.starting';
    }

    public function broadcastWith(): array
    {
        return [
            'stage_number' => $this->stageNumber,
            'total_stages' => $this->room->total_stages,
            'selected_game' => [
                'id' => $this->game->id,
                'slug' => $this->game->slug,
                'title' => $this->game->title,
                'description' => $this->game->description,
            ],
            'all_games' => $this->allGames,
            'timer_seconds' => $this->room->stage_timer,
            'active_players' => $this->room->activePlayers()->count(),
        ];
    }
}
