<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Game;
use App\Models\RoomParticipant;
use App\Models\BabakBelurStage;
use App\Events\StageStarting;
use App\Events\StageEnded;
use App\Events\BabakBelurFinished;
use App\Events\GameStarted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BabakBelurController extends Controller
{
    /**
     * Show the Babak Belur game arena.
     */
    public function arena($code)
    {
        $room = Room::where('code', $code)
            ->with(['host', 'participants.user', 'stages.game'])
            ->firstOrFail();

        if ($room->mode !== 'babak_belur') {
            return redirect()->route('rooms.lobby', $code);
        }

        $participant = RoomParticipant::where('room_id', $room->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$participant) {
            return redirect()->route('rooms.create')
                ->withErrors(['msg' => 'Kamu bukan peserta room ini.']);
        }

        $games = Game::with(['levels' => fn($q) => $q->orderBy('order')])->get();
        $currentStage = $room->stages()->where('status', 'active')->with('game.levels')->first();

        return view('rooms.babak-belur', compact('room', 'participant', 'games', 'currentStage'));
    }

    /**
     * Start the next stage (host only). Picks a random game via gacha.
     */
    public function startStage(Request $request, $code)
    {
        $room = Room::where('code', $code)->with('participants')->firstOrFail();

        if ($room->host_id !== Auth::id()) {
            return response()->json(['error' => 'Hanya host yang bisa memulai stage.'], 403);
        }

        $nextStage = ($room->current_stage ?? 0) + 1;

        if ($nextStage > $room->total_stages) {
            return response()->json(['error' => 'Semua stage sudah selesai.'], 422);
        }

        // Pick games already used in this room
        $usedGameIds = BabakBelurStage::where('room_id', $room->id)->pluck('game_id')->toArray();

        // Get all available games, excluding used ones if possible
        $availableGames = Game::whereNotIn('id', $usedGameIds)->get();
        if ($availableGames->isEmpty()) {
            $availableGames = Game::all(); // fallback: allow repeats
        }

        // Random pick
        $selectedGame = $availableGames->random();

        // All games for carousel display
        $allGames = Game::all()->map(fn($g) => [
            'id' => $g->id,
            'slug' => $g->slug,
            'title' => $g->title,
        ])->toArray();

        // Create stage record
        $stage = BabakBelurStage::create([
            'room_id' => $room->id,
            'stage_number' => $nextStage,
            'game_id' => $selectedGame->id,
            'status' => 'active',
            'started_at' => now(),
        ]);

        // Update room
        $room->update([
            'current_stage' => $nextStage,
            'status' => 'active',
        ]);

        // Reset stage scores for active participants
        RoomParticipant::where('room_id', $room->id)
            ->where('is_eliminated', false)
            ->update([
                'stage_score' => 0,
                'current_level' => 1,
            ]);

        // Broadcast the carousel + game selection
        broadcast(new StageStarting($room, $nextStage, $selectedGame, $allGames));

        return response()->json([
            'success' => true,
            'stage' => $nextStage,
            'game' => [
                'id' => $selectedGame->id,
                'slug' => $selectedGame->slug,
                'title' => $selectedGame->title,
            ],
        ]);
    }

    /**
     * Update a participant's score during a babak belur stage.
     */
    public function updateStageScore(Request $request, $code)
    {
        $request->validate([
            'stage_score' => 'required|integer|min:0',
            'current_level' => 'required|integer|min:1',
        ]);

        $room = Room::where('code', $code)->firstOrFail();

        $participant = RoomParticipant::where('room_id', $room->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($participant->is_eliminated) {
            return response()->json(['error' => 'Kamu sudah tereliminasi.'], 403);
        }

        $participant->update([
            'stage_score' => $request->stage_score,
            'current_level' => $request->current_level,
            'score' => $participant->score + max(0, $request->stage_score - $participant->stage_score),
        ]);

        // Broadcast score update
        broadcast(new \App\Events\ScoreUpdated(
            $room,
            Auth::user(),
            $participant->fresh()->stage_score,
            $request->current_level
        ))->toOthers();

        return response()->json([
            'success' => true,
            'stage_score' => $participant->fresh()->stage_score,
            'total_score' => $participant->fresh()->score,
        ]);
    }

    /**
     * End the current stage (host only). Calculate eliminations.
     */
    public function endStage(Request $request, $code)
    {
        $room = Room::where('code', $code)->with('participants.user')->firstOrFail();

        if ($room->host_id !== Auth::id()) {
            return response()->json(['error' => 'Hanya host yang bisa mengakhiri stage.'], 403);
        }

        $currentStageNum = $room->current_stage;
        $stage = BabakBelurStage::where('room_id', $room->id)
            ->where('stage_number', $currentStageNum)
            ->where('status', 'active')
            ->firstOrFail();

        // Get active participants sorted by stage_score desc
        $activePlayers = $room->participants()
            ->where('is_eliminated', false)
            ->orderByDesc('stage_score')
            ->get();

        $totalActive = $activePlayers->count();
        $isFinal = $currentStageNum >= $room->total_stages;

        // Calculate how many qualify
        if ($isFinal) {
            $qualifyCount = 1; // only the winner
        } else {
            // roughly 50% survive each round, minimum 2
            $qualifyCount = max(2, (int) ceil($totalActive / 2));
        }

        $qualifiedPlayers = $activePlayers->take($qualifyCount);
        $eliminatedPlayers = $activePlayers->skip($qualifyCount);

        $qualifiedIds = $qualifiedPlayers->pluck('user_id')->toArray();
        $eliminatedIds = $eliminatedPlayers->pluck('user_id')->toArray();

        // Mark eliminated participants
        RoomParticipant::where('room_id', $room->id)
            ->whereIn('user_id', $eliminatedIds)
            ->update([
                'is_eliminated' => true,
                'eliminated_at_stage' => $currentStageNum,
                'is_spectator' => true,
            ]);

        // Build rankings for this stage
        $rankings = $activePlayers->map(function ($p, $index) use ($qualifiedIds) {
            return [
                'user_id' => $p->user_id,
                'username' => $p->user->name ?? 'Unknown',
                'avatar' => $p->user->avatar,
                'stage_score' => $p->stage_score,
                'total_score' => $p->score,
                'rank' => $index + 1,
                'qualified' => in_array($p->user_id, $qualifiedIds),
            ];
        })->toArray();

        // Update stage record
        $stage->update([
            'status' => 'finished',
            'qualified_count' => count($qualifiedIds),
            'eliminated_count' => count($eliminatedIds),
            'finished_at' => now(),
        ]);

        // If final stage, finish the game
        if ($isFinal) {
            $room->update([
                'status' => 'finished',
                'finished_at' => now(),
            ]);

            $winner = $qualifiedPlayers->first();
            $winnerData = [
                'user_id' => $winner->user_id,
                'username' => $winner->user->name ?? 'Unknown',
                'avatar' => $winner->user->avatar,
                'total_score' => $winner->score,
            ];

            broadcast(new BabakBelurFinished($room, $winnerData, $rankings));
        } else {
            broadcast(new StageEnded($room, $currentStageNum, $qualifiedIds, $eliminatedIds, $rankings));
        }

        return response()->json([
            'success' => true,
            'stage' => $currentStageNum,
            'is_final' => $isFinal,
            'qualified' => count($qualifiedIds),
            'eliminated' => count($eliminatedIds),
            'rankings' => $rankings,
        ]);
    }

    /**
     * Get current stage status (for polling fallback).
     */
    public function stageStatus($code)
    {
        $room = Room::where('code', $code)->firstOrFail();

        $stage = BabakBelurStage::where('room_id', $room->id)
            ->where('stage_number', $room->current_stage)
            ->with('game')
            ->first();

        $activeCount = $room->activePlayers()->count();

        return response()->json([
            'current_stage' => $room->current_stage,
            'total_stages' => $room->total_stages,
            'status' => $room->status,
            'active_players' => $activeCount,
            'stage' => $stage ? [
                'game_slug' => $stage->game->slug,
                'game_title' => $stage->game->title,
                'status' => $stage->status,
                'started_at' => $stage->started_at?->toISOString(),
            ] : null,
        ]);
    }
}
