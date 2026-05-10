<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'host_id',
        'game_id',
        'status',
        'max_players',
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
     * Generate a unique 6-character alphanumeric room code.
     */
    public static function generateCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    /**
     * The user who hosts this room.
     */
    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    /**
     * The game being played in this room.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * All participants in this room.
     */
    public function participants()
    {
        return $this->hasMany(RoomParticipant::class);
    }

    /**
     * Scope: only rooms with 'active' status.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: only rooms with 'waiting' status.
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }
}
