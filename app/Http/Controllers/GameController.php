<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class GameController extends BaseGameController
{
    public function index()
    {
        $user = Auth::user();

        // Eager load levels + user's progress in a single query to avoid N+1
        $games = Game::with([
            'levels' => function ($query) {
                $query->orderBy('order');
            },
        ])->get();

        // Batch-fetch all progress for this user across all levels
        $allLevelIds = $games->pluck('levels')->flatten()->pluck('id');
        $userProgressMap = UserProgress::where('user_id', $user->id)
            ->whereIn('level_id', $allLevelIds)
            ->get()
            ->groupBy(function ($progress) {
                return $progress->level_id;
            });

        // Attach stats without N+1 queries
        $games->each(function ($game) use ($userProgressMap) {
            $gameLevelIds = $game->levels->pluck('id')->toArray();
            $progress = $userProgressMap->intersectByKeys(array_flip($gameLevelIds))->flatten();
            $game->completed_levels = $progress->where('completed', true)->count();
            $game->total_score = $progress->sum('score');
        });

        return view('games.index', compact('games'));
    }

    public function show($slug)
    {
        $game = $this->getGame($slug);
        
        return view('games.show', compact('game'));
    }

    public function play($slug)
    {
        $game = $this->getGame($slug);
        $levels = $game->levels;
        
        $stats = $this->getGameStats($game, Auth::user());
        $userProgress = $stats['progress_collection']->keyBy('level_id');

        $viewName = "games.variants.{$slug}";
        if (view()->exists($viewName)) {
            return view($viewName, compact('game', 'levels', 'userProgress'));
        }

        return view('games.play', compact('game', 'levels', 'userProgress'));
    }

    public function saveProgress(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:levels,id',
            'score' => 'required|integer|min:0',
            'time_taken' => 'nullable|integer|min:0',
        ]);

        $progress = $this->persistProgress(
            Auth::id(), 
            $request->level_id, 
            $request->score, 
            $request->time_taken
        );

        // Bust leaderboard caches so new scores appear immediately
        Cache::forget('leaderboard.global');
        $gameSlug = $progress->level->game->slug ?? null;
        if ($gameSlug) {
            Cache::forget("leaderboard.game.{$gameSlug}");
        }

        $gameId = $progress->level->game_id;
        $game = Game::with('levels')->find($gameId);
        $stats = $this->getGameStats($game, Auth::user());

        $nextLevel = \App\Models\Level::where('game_id', $gameId)
            ->where('order', '>', $progress->level->order)
            ->orderBy('order')
            ->first();

        return response()->json([
            'success' => true,
            'score' => $progress->score,
            'total_score' => $stats['total_score'],
            'next_level' => $nextLevel ? $nextLevel->id : null,
        ]);
    }

    public function complete($slug)
    {
        $game = $this->getGame($slug);
        $stats = $this->getGameStats($game, Auth::user());
        
        $levelIds = $game->levels->pluck('id');
        $leaderboard = UserProgress::with('user')
            ->whereIn('level_id', $levelIds)
            ->select('user_id', \Illuminate\Support\Facades\DB::raw('SUM(score) as total_score'))
            ->groupBy('user_id')
            ->orderByDesc('total_score')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return (object)[
                    'name' => $item->user->name,
                    'avatar' => $item->user->avatar,
                    'total_score' => $item->total_score,
                ];
            });

        return view('games.complete', compact('game', 'stats', 'leaderboard'));
    }
}
