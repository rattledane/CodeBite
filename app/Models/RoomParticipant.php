<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomParticipant extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_id',
        'user_id',
        'score',
        'current_level',
        'rank',
        'is_finished',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'is_finished' => 'boolean',
            'finished_at' => 'datetime',
        ];
    }

    /**
     * The room this participant belongs to.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * The user who is participating.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
