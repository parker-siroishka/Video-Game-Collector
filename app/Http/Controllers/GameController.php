<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Auth;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\User;

class GameController extends Controller
{
    /**
     * Handle an incoming created game
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'coverart' => 'required',
            'playtime' => 'required|integer'
        ]);

        $game = Game::create([
            'title' => $request->title,
            'coverart' => $request->coverart,
            'playtime' => $request->playtime,
        ]);

        return redirect(route('dashboard', absolute: false));
    }

    public function getUserGames(Request $request) 
    {
        $currentUserId = $request->user()->id;
        $games = Game::where('user_id', $currentUserId)->get();
        return response()->json( $games );
    }
}
