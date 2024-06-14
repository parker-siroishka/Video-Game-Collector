<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
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

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
                // Validate the request data
            $validatedData = $request->validate([
                'is_active' => 'boolean',
                'is_paused' => 'boolean',
                'start_session' => 'date',
                'end_session' => 'date',
                'duration_milliseconds' => 'integer',
                'duration_humanized' => 'string'
            ]);

            // Find the play session by id and current session's game.
            // Add session duration to game's playtime
            // update model with the validated data
            $playSession = PlaySession::findOrFail($id);
            $game = $playSession->game;

            $durationInHours = $validatedData['duration_milliseconds'] / 3600000;
            $game->playtime += $durationInHours;

            // Attempt to save the game and play session
            $game->save();
            $playSession->update($validatedData);

            DB::commit();
            return response()->json(['success' => 'PlaySession patched successfully.'], 204);
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception, log it, and return an error response
            return response()->json(['error' => 'Update failed.'], 500);
        }
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
