<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Level;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the global leaderboard page shows users ordered by total XP.
     */
    public function test_leaderboard_shows_top_users(): void
    {
        Cache::flush();

        $game = Game::factory()->create(['slug' => 'test-game']);
        $level1 = Level::factory()->create(['game_id' => $game->id, 'order' => 1]);
        $level2 = Level::factory()->create(['game_id' => $game->id, 'order' => 2]);

        $topUser = User::factory()->create(['name' => 'Top Player']);
        $midUser = User::factory()->create(['name' => 'Mid Player']);
        $lowUser = User::factory()->create(['name' => 'Low Player']);

        // Top Player: 90 + 80 = 170
        UserProgress::create(['user_id' => $topUser->id, 'level_id' => $level1->id, 'score' => 90, 'completed' => true, 'attempts' => 1]);
        UserProgress::create(['user_id' => $topUser->id, 'level_id' => $level2->id, 'score' => 80, 'completed' => true, 'attempts' => 1]);

        // Mid Player: 60 + 50 = 110
        UserProgress::create(['user_id' => $midUser->id, 'level_id' => $level1->id, 'score' => 60, 'completed' => true, 'attempts' => 1]);
        UserProgress::create(['user_id' => $midUser->id, 'level_id' => $level2->id, 'score' => 50, 'completed' => true, 'attempts' => 1]);

        // Low Player: 30
        UserProgress::create(['user_id' => $lowUser->id, 'level_id' => $level1->id, 'score' => 30, 'completed' => true, 'attempts' => 1]);

        $response = $this->actingAs($topUser)->get('/leaderboard');

        $response->assertStatus(200);
        $response->assertSee('Top Player');
        $response->assertSee('Mid Player');
        $response->assertSee('Low Player');

        // Verify the order: Top Player should appear before Mid Player in the response
        $content = $response->getContent();
        $topPos = strpos($content, 'Top Player');
        $midPos = strpos($content, 'Mid Player');
        $lowPos = strpos($content, 'Low Player');

        $this->assertLessThan($midPos, $topPos, 'Top Player should appear before Mid Player');
        $this->assertLessThan($lowPos, $midPos, 'Mid Player should appear before Low Player');
    }

    /**
     * Test that the per-game leaderboard only shows scores from that specific game.
     */
    public function test_per_game_leaderboard_correct(): void
    {
        Cache::flush();

        $game1 = Game::factory()->create(['slug' => 'game-one', 'title' => 'Game One']);
        $game2 = Game::factory()->create(['slug' => 'game-two', 'title' => 'Game Two']);

        $level1 = Level::factory()->create(['game_id' => $game1->id, 'order' => 1]);
        $level2 = Level::factory()->create(['game_id' => $game2->id, 'order' => 1]);

        $userA = User::factory()->create(['name' => 'Alpha Player']);
        $userB = User::factory()->create(['name' => 'Beta Player']);

        // Alpha has high score in game1, low in game2
        UserProgress::create(['user_id' => $userA->id, 'level_id' => $level1->id, 'score' => 100, 'completed' => true, 'attempts' => 1]);
        UserProgress::create(['user_id' => $userA->id, 'level_id' => $level2->id, 'score' => 10, 'completed' => true, 'attempts' => 1]);

        // Beta has low score in game1, high in game2
        UserProgress::create(['user_id' => $userB->id, 'level_id' => $level1->id, 'score' => 20, 'completed' => true, 'attempts' => 1]);
        UserProgress::create(['user_id' => $userB->id, 'level_id' => $level2->id, 'score' => 95, 'completed' => true, 'attempts' => 1]);

        // View Game One leaderboard — Alpha should be on top
        $response = $this->actingAs($userA)->get('/leaderboard/game-one');

        $response->assertStatus(200);
        $response->assertSee('Alpha Player');
        $response->assertSee('Beta Player');

        $content = $response->getContent();
        $alphaPos = strpos($content, 'Alpha Player');
        $betaPos = strpos($content, 'Beta Player');
        $this->assertLessThan($betaPos, $alphaPos, 'Alpha should rank above Beta in Game One');

        // View Game Two leaderboard — Beta should be on top
        Cache::flush();
        $response2 = $this->actingAs($userA)->get('/leaderboard/game-two');

        $response2->assertStatus(200);
        $content2 = $response2->getContent();

        // Extract just the tbody section to avoid nav/tab area matches
        $tbodyStart2 = strpos($content2, '<tbody>');
        $tbody2 = substr($content2, $tbodyStart2);
        $betaPos2 = strpos($tbody2, 'Beta Player');
        $alphaPos2 = strpos($tbody2, 'Alpha Player');
        $this->assertLessThan($alphaPos2, $betaPos2, 'Beta should rank above Alpha in Game Two');
    }
}
