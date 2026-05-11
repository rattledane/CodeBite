<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StageEnded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $stageNumber;
    public $qualifiedIds;
    public $eliminatedIds;
    public $rankings;

    public function __construct(Room $room, int $stageNumber, array $qualifiedIds, array $eliminatedIds, array $rankings)
    {
        $this->room = $room;
        $this->stageNumber = $stageNumber;
        $this->qualifiedIds = $qualifiedIds;
        $this->eliminatedIds = $eliminatedIds;
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
        return 'stage.ended';
    }

    public function broadcastWith(): array
    {
        $isFinal = $this->stageNumber >= $this->room->total_stages;

        return [
            'stage_number' => $this->stageNumber,
            'total_stages' => $this->room->total_stages,
            'is_final' => $isFinal,
            'qualified_ids' => $this->qualifiedIds,
            'eliminated_ids' => $this->eliminatedIds,
            'rankings' => $this->rankings,
            'next_stage' => $isFinal ? null : $this->stageNumber + 1,
        ];
    }
}
