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
    // public function store(Request $request): RedirectResponse
    // {
    //     $request->validate([

    //     ]);
    // }

    public function getUserGames(Request $request) 
    {
        $currentUserId = $request->user()->id;
        $games = Game::where('user_id', $currentUserId)->get();
        return response()->json( $games );
    }
}
