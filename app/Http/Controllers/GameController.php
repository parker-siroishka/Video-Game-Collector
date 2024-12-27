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
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            // todo make coverart nullable
            'coverart' => 'string|nullable',
            'playtime' => 'required|integer',
            'estimated_playtime' => 'integer',
            'console' => 'string'
        ]);

        $game = Game::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'coverart' => $request->coverart,
            'playtime' => $request->playtime,
            'estimated_playtime' => $request->estimatedPlaytime,
            'console' => $request->console,
        ]);

        return response()->json(['success' => 'Game created successfully.', 'game' => $game]);
    }

    public function getUserGames(Request $request) 
    {
        $currentUserId = $request->user()->id;
        $games = Game::where('user_id', $currentUserId)->orderBy('title', 'asc')->get();
        return response()->json( $games );
    }

    public function getUserGame(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return response()->json(['message' => 'The id must be an integer.'], 422);
        }

        $currentUserId = $request->user()->id;
        $games = Game::where('user_id', $currentUserId)
            ->where('id', $id)
            ->get();

        return response()->json($games);
    }


    public function getUniqueConsoles(Request $request)
    {
        $currentUserId = $request->user()->id;
        $uniqueConsoles = Game::where('user_id', $currentUserId)
                            ->distinct()
                            ->get(['console']);

        return response()->json($uniqueConsoles);
    }

}
