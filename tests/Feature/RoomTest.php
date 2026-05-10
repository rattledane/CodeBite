<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Level;
use App\Models\Room;
use App\Models\RoomParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a host can create a new room and is added as the first participant.
     */
    public function test_host_can_create_room(): void
    {
        Event::fake();

        $host = User::factory()->create();
        $game = Game::factory()->create();
        Level::factory()->create(['game_id' => $game->id, 'order' => 1]);

        $response = $this->actingAs($host)->post('/rooms/create', [
            'game_id' => $game->id,
        ]);

        $this->assertDatabaseCount('rooms', 1);

        $room = Room::first();
        $this->assertEquals($host->id, $room->host_id);
        $this->assertEquals($game->id, $room->game_id);
        $this->assertEquals('waiting', $room->status);

        // Host should be auto-added as participant
        $this->assertDatabaseHas('room_participants', [
            'room_id' => $room->id,
            'user_id' => $host->id,
        ]);

        $response->assertRedirect(route('rooms.lobby', $room->code));
    }

    /**
     * Test that a participant can join a room using a valid code.
     */
    public function test_participant_can_join_with_valid_code(): void
    {
        Event::fake();

        $host = User::factory()->create();
        $joiner = User::factory()->create();
        $game = Game::factory()->create();

        $room = Room::factory()->create([
            'host_id' => $host->id,
            'game_id' => $game->id,
            'status' => 'waiting',
            'max_players' => 10,
        ]);
        RoomParticipant::factory()->create([
            'room_id' => $room->id,
            'user_id' => $host->id,
        ]);

        $response = $this->actingAs($joiner)->post('/rooms/join', [
            'code' => $room->code,
        ]);

        $response->assertRedirect(route('rooms.lobby', $room->code));

        $this->assertDatabaseHas('room_participants', [
            'room_id' => $room->id,
            'user_id' => $joiner->id,
        ]);
    }

    /**
     * Test that a user cannot join a room that is already full.
     */
    public function test_cannot_join_full_room(): void
    {
        Event::fake();

        $host = User::factory()->create();
        $game = Game::factory()->create();

        $room = Room::factory()->create([
            'host_id' => $host->id,
            'game_id' => $game->id,
            'status' => 'waiting',
            'max_players' => 2,
        ]);

        // Fill the room with 2 participants
        RoomParticipant::factory()->create(['room_id' => $room->id, 'user_id' => $host->id]);
        $player2 = User::factory()->create();
        RoomParticipant::factory()->create(['room_id' => $room->id, 'user_id' => $player2->id]);

        // Third user tries to join
        $player3 = User::factory()->create();
        $response = $this->actingAs($player3)->post('/rooms/join', [
            'code' => $room->code,
        ]);

        $response->assertSessionHasErrors('code');

        $this->assertDatabaseMissing('room_participants', [
            'room_id' => $room->id,
            'user_id' => $player3->id,
        ]);
    }

    /**
     * Test that only the host can start the race.
     */
    public function test_only_host_can_start_race(): void
    {
        Event::fake();

        $host = User::factory()->create();
        $player = User::factory()->create();
        $game = Game::factory()->create();

        $room = Room::factory()->create([
            'host_id' => $host->id,
            'game_id' => $game->id,
            'status' => 'waiting',
        ]);
        RoomParticipant::factory()->create(['room_id' => $room->id, 'user_id' => $host->id]);
        RoomParticipant::factory()->create(['room_id' => $room->id, 'user_id' => $player->id]);

        // Non-host tries to start → should get 403
        $response = $this->actingAs($player)->post("/rooms/{$room->code}/start");
        $response->assertStatus(403);

        // Room should still be waiting
        $this->assertEquals('waiting', $room->fresh()->status);
    }

    /**
     * Test that room status changes to 'active' when the host starts the game.
     */
    public function test_room_status_changes_to_active_on_start(): void
    {
        Event::fake();

        $host = User::factory()->create();
        $player = User::factory()->create();
        $game = Game::factory()->create();

        $room = Room::factory()->create([
            'host_id' => $host->id,
            'game_id' => $game->id,
            'status' => 'waiting',
        ]);
        RoomParticipant::factory()->create(['room_id' => $room->id, 'user_id' => $host->id]);
        RoomParticipant::factory()->create(['room_id' => $room->id, 'user_id' => $player->id]);

        $response = $this->actingAs($host)->post("/rooms/{$room->code}/start");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $room->refresh();
        $this->assertEquals('active', $room->status);
        $this->assertNotNull($room->started_at);
    }
}
