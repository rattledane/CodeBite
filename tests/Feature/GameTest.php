<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Level;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the games lobby page lists all available games.
     */
    public function test_games_lobby_shows_all_games(): void
    {
        $user = User::factory()->create();

        $game1 = Game::factory()->create(['title' => 'CSS Selector', 'slug' => 'css-selector']);
        $game2 = Game::factory()->create(['title' => 'Grid Garden', 'slug' => 'grid-garden']);
        $game3 = Game::factory()->create(['title' => 'HTML Builder', 'slug' => 'html-tag-builder']);

        // Each game needs at least one level for stats to work
        Level::factory()->create(['game_id' => $game1->id, 'order' => 1]);
        Level::factory()->create(['game_id' => $game2->id, 'order' => 1]);
        Level::factory()->create(['game_id' => $game3->id, 'order' => 1]);

        $response = $this->actingAs($user)->get('/games');

        $response->assertStatus(200);
        $response->assertSee('CSS Selector');
        $response->assertSee('Grid Garden');
        $response->assertSee('HTML Builder');
    }

    /**
     * Test that an authenticated user can access a game's play page.
     */
    public function test_can_access_game_play_page(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->create(['slug' => 'css-selector']);
        Level::factory()->create(['game_id' => $game->id, 'order' => 1]);

        $response = $this->actingAs($user)->get("/games/{$game->slug}/play");

        $response->assertStatus(200);
    }

    /**
     * Test that progress is saved correctly when submitting a score.
     */
    public function test_progress_saved_correctly(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->create(['slug' => 'test-game']);
        $level = Level::factory()->create([
            'game_id' => $game->id,
            'order' => 1,
            'max_score' => 100,
        ]);

        $response = $this->actingAs($user)->postJson('/games/progress', [
            'level_id' => $level->id,
            'score' => 80,
            'time_taken' => 25,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'score' => 80]);

        $this->assertDatabaseHas('user_progress', [
            'user_id' => $user->id,
            'level_id' => $level->id,
            'score' => 80,
            'time_taken' => 25,
            'completed' => true,
            'attempts' => 1,
        ]);
    }

    /**
     * Test that score is NOT updated if the new score is lower than the existing one.
     * The attempts counter should still increment.
     */
    public function test_score_not_updated_if_lower_than_existing(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->create(['slug' => 'test-game']);
        $level = Level::factory()->create([
            'game_id' => $game->id,
            'order' => 1,
            'max_score' => 100,
        ]);

        // First attempt — score 90
        $this->actingAs($user)->postJson('/games/progress', [
            'level_id' => $level->id,
            'score' => 90,
            'time_taken' => 20,
        ]);

        $this->assertDatabaseHas('user_progress', [
            'user_id' => $user->id,
            'level_id' => $level->id,
            'score' => 90,
            'attempts' => 1,
        ]);

        // Second attempt — lower score of 50
        $response = $this->actingAs($user)->postJson('/games/progress', [
            'level_id' => $level->id,
            'score' => 50,
            'time_taken' => 30,
        ]);

        $response->assertStatus(200);

        // Score should remain 90 (higher), but attempts should be 2
        $progress = UserProgress::where('user_id', $user->id)
            ->where('level_id', $level->id)
            ->first();

        $this->assertEquals(90, $progress->score);
        $this->assertEquals(20, $progress->time_taken);
        $this->assertEquals(2, $progress->attempts);
    }
}
