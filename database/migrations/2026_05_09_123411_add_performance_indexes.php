<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add composite indexes for frequently queried columns.
     */
    public function up(): void
    {
        // levels: speed up lookups by game_id + order (used in getGame, next level queries)
        Schema::table('levels', function (Blueprint $table) {
            $table->index(['game_id', 'order'], 'idx_levels_game_order');
        });

        // user_progress: speed up per-user progress queries (already has unique on user_id+level_id,
        // but add explicit index on user_id alone for WHERE user_id = ? queries)
        Schema::table('user_progress', function (Blueprint $table) {
            $table->index('user_id', 'idx_user_progress_user');
        });

        // rooms: verify code has an index (it's unique, so it does — add status index for scopes)
        Schema::table('rooms', function (Blueprint $table) {
            $table->index('status', 'idx_rooms_status');
        });

        // room_participants: index on room_id alone for count/lookup queries
        Schema::table('room_participants', function (Blueprint $table) {
            $table->index('room_id', 'idx_room_participants_room');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropIndex('idx_levels_game_order');
        });

        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropIndex('idx_user_progress_user');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropIndex('idx_rooms_status');
        });

        Schema::table('room_participants', function (Blueprint $table) {
            $table->dropIndex('idx_room_participants_room');
        });
    }
};
