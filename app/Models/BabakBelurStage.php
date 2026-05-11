<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabakBelurStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'stage_number',
        'game_id',
        'status',
        'qualified_count',
        'eliminated_count',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    /**
     * The room this stage belongs to.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * The game played in this stage.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
