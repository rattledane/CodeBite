<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\UserProgress;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class BaseGameController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Get a game by its slug, throwing a 404 if not found.
     */
    protected function getGame($slug)
    {
        return Game::with(['levels' => function ($query) {
            $query->orderBy('order');
        }])->where('slug', $slug)->firstOrFail();
    }

    /**
     * Get the next uncompleted level for the user.
     */
    protected function getCurrentLevel($game, $user)
    {
        $completedLevelIds = UserProgress::where('user_id', $user->id)
            ->whereIn('level_id', $game->levels->pluck('id'))
            ->pluck('level_id')
            ->toArray();

        foreach ($game->levels as $level) {
            if (!in_array($level->id, $completedLevelIds)) {
                return $level;
            }
        }

        // If all completed, return the last level
        return $game->levels->last();
    }

    /**
     * Reusable scoring formula for backend validation if necessary.
     */
    protected function calculateScore($timeRemaining, $maxScore, $attempts)
    {
        if ($timeRemaining <= 0 && $attempts > 0) {
            return 0;
        }

        if ($timeRemaining > 45) {
            $score = $maxScore;
        } elseif ($timeRemaining >= 30) {
            $score = floor($maxScore * 0.8);
        } elseif ($timeRemaining >= 15) {
            $score = floor($maxScore * 0.6);
        } else {
            $score = floor($maxScore * 0.4);
        }

        if ($attempts === 1) {
            $score += 10;
        }

        return $score;
    }

    /**
     * Save or update progress for a user on a specific level.
     */
    protected function persistProgress($userId, $levelId, $score, $timeTaken)
    {
        $progress = UserProgress::firstOrNew([
            'user_id' => $userId,
            'level_id' => $levelId,
        ]);

        $progress->attempts = $progress->exists ? $progress->attempts + 1 : 1;

        if (!$progress->exists || $score > $progress->score) {
            $progress->score = $score;
            $progress->time_taken = $timeTaken;
        }

        if ($score > 0 || !$progress->exists) {
            $progress->completed = true;
        }

        $progress->save();
        $progress->load('level.game');

        return $progress;
    }

    /**
     * Calculate global game statistics for a specific user.
     */
    protected function getGameStats($game, $user)
    {
        $progress = UserProgress::where('user_id', $user->id)
            ->whereIn('level_id', $game->levels->pluck('id'))
            ->get();

        $totalScore = $progress->sum('score');
        $averageTime = $progress->count() > 0 ? round($progress->avg('time_taken')) : 0;
        $completedLevelsCount = $progress->where('completed', true)->count();
        $totalLevels = $game->levels->count();
        $completionPercentage = $totalLevels > 0 ? round(($completedLevelsCount / $totalLevels) * 100) : 0;

        return [
            'total_score' => $totalScore,
            'average_time' => $averageTime,
            'completed_levels' => $completedLevelsCount,
            'total_levels' => $totalLevels,
            'completion_percentage' => $completionPercentage,
            'progress_collection' => $progress
        ];
    }

    /**
     * Check if a user has completed all levels in a game.
     */
    protected function isGameCompleted($game, $user)
    {
        $completedCount = UserProgress::where('user_id', $user->id)
            ->whereIn('level_id', $game->levels->pluck('id'))
            ->where('completed', true)
            ->count();

        return $game->levels->count() > 0 && $completedCount === $game->levels->count();
    }
}
