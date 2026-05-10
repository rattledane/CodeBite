<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomParticipant;
use App\Events\PlayerJoined;
use App\Events\GameStarted;
use App\Events\ScoreUpdated;
use App\Events\RaceFinished;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Show room creation form.
     */
    public function showCreate()
    {
        $games = \App\Models\Game::all();
        return view('rooms.create', compact('games'));
    }

    /**
     * Create a new room and redirect to lobby.
     */
    public function create(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
        ]);

        $room = Room::create([
            'code' => Room::generateCode(),
            'host_id' => Auth::id(),
            'game_id' => $request->game_id,
            'status' => 'waiting',
            'max_players' => $request->max_players ?? 10,
        ]);

        // Add host as first participant
        RoomParticipant::create([
            'room_id' => $room->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('rooms.lobby', $room->code);
    }

    /**
     * Join an existing room by code.
     */
    public function join(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $code = strtoupper($request->code);
        $room = Room::where('code', $code)->firstOrFail();

        if ($room->status !== 'waiting') {
            return back()->withErrors(['code' => 'Room ini sudah dimulai atau selesai.']);
        }

        if ($room->participants()->count() >= $room->max_players) {
            return back()->withErrors(['code' => 'Room sudah penuh.']);
        }

        // Add participant if not already in room
        $existing = RoomParticipant::where('room_id', $room->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$existing) {
            RoomParticipant::create([
                'room_id' => $room->id,
                'user_id' => Auth::id(),
            ]);

            broadcast(new PlayerJoined($room, Auth::user()))->toOthers();
        }

        return redirect()->route('rooms.lobby', $room->code);
    }

    /**
     * Show the room lobby (waiting room).
     */
    public function lobby($code)
    {
        $room = Room::where('code', $code)
            ->with(['game', 'host', 'participants.user'])
            ->firstOrFail();

        // Check user is a participant, if not and room is waiting, auto-join
        $isParticipant = $room->participants->where('user_id', Auth::id())->count() > 0;

        if (!$isParticipant && $room->status === 'waiting' && $room->participants->count() < $room->max_players) {
            RoomParticipant::create([
                'room_id' => $room->id,
                'user_id' => Auth::id(),
            ]);

            $room->load('participants.user');
            broadcast(new PlayerJoined($room, Auth::user()))->toOthers();
        } elseif (!$isParticipant) {
            return redirect()->route('games.index')->withErrors(['msg' => 'Tidak bisa bergabung ke room ini.']);
        }

        $participants = $room->participants->sortByDesc('score');

        return view('rooms.lobby', compact('room', 'participants'));
    }

    /**
     * Start the game (host only).
     */
    public function start($code)
    {
        $room = Room::where('code', $code)->with(['participants', 'game'])->firstOrFail();

        if ($room->host_id !== Auth::id()) {
            return response()->json(['error' => 'Hanya host yang bisa memulai game.'], 403);
        }

        if ($room->participants->count() < 2) {
            return response()->json(['error' => 'Minimal 2 pemain untuk memulai.'], 422);
        }

        $room->update([
            'status' => 'active',
            'started_at' => now(),
        ]);

        broadcast(new GameStarted($room));

        return response()->json(['success' => true, 'message' => 'Game dimulai!']);
    }

    /**
     * Update a participant's score during a race.
     */
    public function updateScore(Request $request, $code)
    {
        $request->validate([
            'score' => 'required|integer|min:0',
            'current_level' => 'required|integer|min:1',
        ]);

        $room = Room::where('code', $code)->firstOrFail();

        $participant = RoomParticipant::where('room_id', $room->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $participant->update([
            'score' => $request->score,
            'current_level' => $request->current_level,
        ]);

        broadcast(new ScoreUpdated($room, Auth::user(), $request->score, $request->current_level))->toOthers();

        return response()->json(['success' => true, 'score' => $participant->score]);
    }

    /**
     * Mark the current user as finished and calculate rankings.
     */
    public function finish($code)
    {
        $room = Room::where('code', $code)->with('participants')->firstOrFail();

        $participant = RoomParticipant::where('room_id', $room->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $participant->update([
            'is_finished' => true,
            'finished_at' => now(),
        ]);

        // Calculate ranks based on score (descending)
        $allParticipants = $room->participants()->orderByDesc('score')->get();
        $rank = 1;
        foreach ($allParticipants as $p) {
            $p->update(['rank' => $rank]);
            $rank++;
        }

        // Check if all participants are finished
        $allFinished = $room->participants()->where('is_finished', false)->count() === 0;

        if ($allFinished) {
            $room->update([
                'status' => 'finished',
                'finished_at' => now(),
            ]);

            broadcast(new RaceFinished($room, $allParticipants));
        }

        return response()->json([
            'success' => true,
            'rank' => $participant->fresh()->rank,
            'all_finished' => $allFinished,
        ]);
    }

    /**
     * Restart the room (host only).
     */
    public function restart($code)
    {
        $room = Room::where('code', $code)->firstOrFail();

        if ($room->host_id !== Auth::id()) {
            return response()->json(['error' => 'Hanya host yang bisa merestart game.'], 403);
        }

        $room->update([
            'status' => 'waiting',
            'started_at' => null,
            'finished_at' => null,
        ]);

        RoomParticipant::where('room_id', $room->id)->update([
            'score' => 0,
            'current_level' => 1,
            'is_finished' => false,
            'finished_at' => null,
            'rank' => null,
        ]);

        broadcast(new \App\Events\RoomRestarted($room));

        return response()->json(['success' => true]);
    }
}
