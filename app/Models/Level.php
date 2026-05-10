<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $fillable = [
        'game_id',
        'order',
        'instruction',
        'initial_code',
        'answer_key',
        'max_score',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
