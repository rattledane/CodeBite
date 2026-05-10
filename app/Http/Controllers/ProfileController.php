<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\RoomParticipant;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Total XP
        $totalXp = UserProgress::where('user_id', $user->id)->sum('score');

        // Games completed (all levels completed)
        $games = Game::with('levels')->get();
        $completedGamesCount = 0;
        $gameProgress = [];

        $userCompletedLevels = UserProgress::where('user_id', $user->id)
            ->where('completed', true)
            ->pluck('level_id')
            ->toArray();

        foreach ($games as $game) {
            $totalLevels = $game->levels->count();
            $gameLevelIds = $game->levels->pluck('id')->toArray();
            
            $completedInGame = count(array_intersect($userCompletedLevels, $gameLevelIds));
            
            if ($totalLevels > 0 && $completedInGame === $totalLevels) {
                $completedGamesCount++;
            }

            // progress bar stats
            $gameScores = UserProgress::where('user_id', $user->id)
                ->whereIn('level_id', $gameLevelIds)
                ->get();
            
            $bestScore = $gameScores->sum('score');
            $timePlayed = $gameScores->sum('time_taken'); // in seconds

            $gameProgress[] = [
                'game' => $game,
                'total_levels' => $totalLevels,
                'completed_levels' => $completedInGame,
                'is_completed' => ($totalLevels > 0 && $completedInGame === $totalLevels),
                'best_score' => $bestScore,
                'time_played' => $timePlayed,
            ];
        }

        // Multiplayer races joined
        $racesJoined = RoomParticipant::where('user_id', $user->id)->count();

        // Best global rank
        $allUsersXp = UserProgress::selectRaw('user_id, SUM(score) as total_xp')
            ->groupBy('user_id')
            ->orderByDesc('total_xp')
            ->get();
        
        $rank = '-';
        foreach ($allUsersXp as $index => $u) {
            if ($u->user_id === $user->id) {
                $rank = $index + 1;
                break;
            }
        }

        // Race history table
        $raceHistory = RoomParticipant::with(['room.game', 'room.participants'])
            ->where('user_id', $user->id)
            ->whereNotNull('finished_at')
            ->orderByDesc('finished_at')
            ->paginate(10);

        // Achievement badges logic
        $achievements = [
            'first_win' => RoomParticipant::where('user_id', $user->id)->where('rank', 1)->exists(),
            'speed_demon' => UserProgress::where('user_id', $user->id)->where('time_taken', '<', 10)->exists(),
            'completionist' => $completedGamesCount > 0, // at least one game fully completed
        ];

        return view('profile.index', compact(
            'user', 
            'totalXp', 
            'completedGamesCount', 
            'racesJoined', 
            'rank', 
            'gameProgress', 
            'raceHistory',
            'achievements',
            'games'
        ));
    }
}
