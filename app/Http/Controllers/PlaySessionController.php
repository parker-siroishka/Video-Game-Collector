<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Auth;
use Illuminate\Http\Request;
use App\Models\PlaySession;
use App\Models\User;
use Carbon\Carbon;

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
            'is_paused' => 'boolean',
            'start_session' => 'required|date', // Ensure this is a valid date
            'notes' => 'nullable|string'
        ]);
    
        // Create a new PlaySession instance
        $playSession = new PlaySession;
        $playSession->user_id = $request->user()->id;
        $playSession->game_id = $request->game_id;
        $playSession->is_active = $request->is_active;
        $playSession->is_paused = $request->is_paused;
        $playSession->notes = $request->notes;
        
        // Use Carbon::parse to handle the start_session date
        $playSession->start_session = Carbon::parse($request->start_session, 'UTC');
    
        // Save the new PlaySession
        $playSession->save();
    
        // Return a JSON response with success message and the playSession data
        return response()->json(['success' => 'PlaySession created successfully.'], 201);
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
