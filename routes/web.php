<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect('/games') : redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Authentication Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Game Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::post('/games/progress', [GameController::class, 'saveProgress'])->name('games.progress');
    Route::get('/games/{slug}', [GameController::class, 'show'])->name('games.show');
    Route::get('/games/{slug}/play', [GameController::class, 'play'])->name('games.play');
    Route::get('/games/{slug}/complete', [GameController::class, 'complete'])->name('games.complete');
    
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/leaderboard/{slug}', [LeaderboardController::class, 'game'])->name('leaderboard.game');
    
    Route::get('/rooms/create', [RoomController::class, 'showCreate'])->name('rooms.create');
    Route::post('/rooms/create', [RoomController::class, 'create'])->name('rooms.store');
    Route::post('/rooms/join', [RoomController::class, 'join'])->name('rooms.join');
    Route::get('/rooms/{code}/lobby', [RoomController::class, 'lobby'])->name('rooms.lobby');
    Route::post('/rooms/{code}/start', [RoomController::class, 'start'])->name('rooms.start');
    Route::post('/rooms/{code}/score', [RoomController::class, 'updateScore'])->name('rooms.score');
    Route::post('/rooms/{code}/finish', [RoomController::class, 'finish'])->name('rooms.finish');
    Route::post('/rooms/{code}/restart', [RoomController::class, 'restart'])->name('rooms.restart');

    // Babak Belur (Multiplayer Battle) Routes
    Route::get('/rooms/{code}/babak-belur', [\App\Http\Controllers\BabakBelurController::class, 'arena'])->name('rooms.babak-belur');
    Route::post('/rooms/{code}/babak-belur/start-stage', [\App\Http\Controllers\BabakBelurController::class, 'startStage'])->name('rooms.babak-belur.start-stage');
    Route::post('/rooms/{code}/babak-belur/score', [\App\Http\Controllers\BabakBelurController::class, 'updateStageScore'])->name('rooms.babak-belur.score');
    Route::post('/rooms/{code}/babak-belur/end-stage', [\App\Http\Controllers\BabakBelurController::class, 'endStage'])->name('rooms.babak-belur.end-stage');
    Route::get('/rooms/{code}/babak-belur/status', [\App\Http\Controllers\BabakBelurController::class, 'stageStatus'])->name('rooms.babak-belur.status');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
});
