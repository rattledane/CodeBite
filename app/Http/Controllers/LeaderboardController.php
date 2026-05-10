<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        $games = Game::all();
        $currentSlug = 'global';

        $leaderboard = Cache::remember('leaderboard.global', 300, function () {
            return UserProgress::selectRaw('user_id, SUM(score) as total_score, COUNT(DISTINCT level_id) as levels_completed')
                ->with('user')
                ->groupBy('user_id')
                ->orderByDesc('total_score')
                ->limit(20)
                ->get();
        });

        $userRank = null;
        if (Auth::check()) {
            $userRank = $this->getUserRank();
        }

        return view('leaderboard.index', compact('leaderboard', 'games', 'currentSlug', 'userRank'));
    }

    public function game($slug)
    {
        $game = Game::where('slug', $slug)->firstOrFail();
        $games = Game::all();
        $currentSlug = $slug;

        $leaderboard = Cache::remember("leaderboard.game.{$slug}", 300, function () use ($game) {
            $levelIds = $game->levels()->pluck('id');
            return UserProgress::selectRaw('user_id, SUM(score) as total_score, COUNT(DISTINCT level_id) as levels_completed')
                ->whereIn('level_id', $levelIds)
                ->with('user')
                ->groupBy('user_id')
                ->orderByDesc('total_score')
                ->limit(20)
                ->get();
        });

        $userRank = null;
        if (Auth::check()) {
            $userRank = $this->getUserRank($game->id);
        }

        return view('leaderboard.index', compact('leaderboard', 'games', 'currentSlug', 'userRank', 'game'));
    }

    private function getUserRank($gameId = null)
    {
        $userId = Auth::id();
        
        $query = UserProgress::selectRaw('user_id, SUM(score) as total_score')
            ->groupBy('user_id');

        if ($gameId) {
            $levelIds = DB::table('levels')->where('game_id', $gameId)->pluck('id');
            $query->whereIn('level_id', $levelIds);
        }

        $allScores = $query->orderByDesc('total_score')->get();
        
        $position = $allScores->search(function ($item) use ($userId) {
            return $item->user_id == $userId;
        });

        if ($position !== false) {
            return [
                'position' => $position + 1,
                'total_score' => $allScores[$position]->total_score
            ];
        }

        return null;
    }
}
