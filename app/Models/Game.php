<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = [
        'slug',
        'title',
        'description',
        'thumbnail',
    ];

    public function levels()
    {
        return $this->hasMany(Level::class);
    }
}
