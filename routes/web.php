<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaySessionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard/Dashboard', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/stats', function () {
    return Inertia::render('Stats/Stats');
})->middleware(['auth', 'verified'])->name('stats');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/games', [GameController::class, 'getUserGames'])->name('games.get');
    Route::post('/games', [GameController::class, 'store'])->name('games.post');
    
    Route::get('/consoles', [GameController::class, 'getUniqueConsoles'])->name('games.getUniqueConsoles');

    Route::post('/playsessions', [PlaySessionController::class, 'store'])->name('playSessions.post');
    Route::get('/playsessions', [PlaySessionController::class, 'getUserPlaySessions'])->name('playSessions.get');
    Route::get('/playsessionsgrouped', [PlaySessionController::class, 'getUserGroupedPlaySessions'])->name('playSessionsGrouped.get');
    Route::patch('/playsessions/{id}', [PlaySessionController::class, 'update'])->name('playSessions.patch');
});

require __DIR__.'/auth.php';
