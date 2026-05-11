<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add mode and stage tracking columns to rooms
        Schema::table('rooms', function (Blueprint $table) {
            $table->enum('mode', ['regular', 'babak_belur'])->default('regular')->after('game_id');
            $table->unsignedTinyInteger('current_stage')->nullable()->after('mode');
            $table->unsignedTinyInteger('total_stages')->default(3)->after('current_stage');
            $table->unsignedInteger('stage_timer')->default(120)->after('total_stages');
        });

        // Add elimination tracking to participants
        Schema::table('room_participants', function (Blueprint $table) {
            $table->boolean('is_eliminated')->default(false)->after('is_finished');
            $table->unsignedTinyInteger('eliminated_at_stage')->nullable()->after('is_eliminated');
            $table->boolean('is_spectator')->default(false)->after('eliminated_at_stage');
            $table->unsignedInteger('stage_score')->default(0)->after('score');
        });

        // Stage history table — tracks each stage's game and results
        Schema::create('babak_belur_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->unsignedTinyInteger('stage_number');
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->enum('status', ['pending', 'active', 'finished'])->default('pending');
            $table->unsignedInteger('qualified_count')->default(0);
            $table->unsignedInteger('eliminated_count')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();

            $table->unique(['room_id', 'stage_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('babak_belur_stages');

        Schema::table('room_participants', function (Blueprint $table) {
            $table->dropColumn(['is_eliminated', 'eliminated_at_stage', 'is_spectator', 'stage_score']);
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['mode', 'current_stage', 'total_stages', 'stage_timer']);
        });
    }
};
