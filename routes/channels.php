<?php

use App\Models\Room;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('room.{code}', function ($user, $code) {
    $room = Room::where('code', $code)->first();
    if (!$room) return false;

    $isParticipant = $room->participants()->where('user_id', $user->id)->exists();
    if (!$isParticipant) return false;

    return [
        'id' => $user->id,
        'name' => $user->name,
        'avatar' => $user->avatar,
    ];
});
