<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Auth;
use Illuminate\Http\Request;
use App\Models\PlaySession;
use App\Models\User;

class PlaySessionController extends Controller
{

    /**
     * Handle an incoming created session
     */
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        $playSession = PlaySession::create([
            'user_id' => $request->user()->id,
            'notes' => $request->notes,
            'is_active' => $request->is_active,
            'game_id' => $request->game_id,
            'session_duration' => 0
        ]);

        return response()->json(['success' => 'playSession created successfully.', 'playSession' => $playSession], 201);
    }

    public function getUserPlaySessions(Request $request) 
    {
        $currentUserId = $request->user()->id;
        
        $playSessions = PlaySession::with('game')
                                    ->where('user_id', $currentUserId)
                                    ->get();

        return response()->json( $playSessions );
    }

}
