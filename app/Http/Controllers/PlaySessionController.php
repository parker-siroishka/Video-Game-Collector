<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
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
            'start_session' => 'required|date',
            'pause_session' => 'nullable|date',
            'play_session' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);
    
        // Create a new PlaySession instance
        $playSession = new PlaySession;
        $playSession->user_id = $request->user()->id;
        $playSession->game_id = $request->game_id;
        $playSession->is_active = $request->is_active;
        $playSession->is_paused = $request->is_paused;
        $playSession->notes = $request->notes;
        
        // Use Carbon::parse to handle the various session date values
        $playSession->start_session = Carbon::parse($request->start_session, 'UTC');
        $playSession->pause_session = Carbon::parse($request->pause_session, 'UTC');
        $playSession->play_session = Carbon::parse($request->play_session, 'UTC');
    
        // Save the new PlaySession
        $playSession->save();
    
        // Return a JSON response with success message and the playSession data
        return response()->json(['success' => 'PlaySession created successfully.'], 201);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
                // Define the validation rules
            $rules = [
                'is_active' => 'boolean',
                'is_paused' => 'boolean',
                'start_session' => 'date',
                'pause_session' => 'nullable|date',
                'play_session' => 'nullable|date',
                'end_session' => 'date',
                'duration_milliseconds' => 'integer',
                'duration_humanized' => 'string',
                'notes' => 'string'
            ];

            // Filter the rules
            $filteredRules = array_intersect_key($rules, $request->all());

            // Validate the request data
            $validatedData = $request->validate($filteredRules);

            // Find the play session by id and current session's game.
            // Add session duration to game's playtime
            // update model with the validated data
            $playSession = PlaySession::findOrFail($id);
            if (array_key_exists('duration_milliseconds', $validatedData)) {
                $game = $playSession->game;

                $durationInHours = $validatedData['duration_milliseconds'] / 3600000;
                $game->playtime += $durationInHours;
    
                // Attempt to save the game and play session
                $game->save();   
            }
            $playSession->update($validatedData);

            DB::commit();
            return response()->json(['success' => 'PlaySession patched successfully.'], 204);
        } catch (\Exception $e) {
            dd($e);
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

    public function getTotalSessionTimeByWeek(Request $request)
    {
        // Define default rules
        $rules = [
            'game_id' => 'integer',
        ];

        // Validate the request data
        $validatedData = $request->validate($rules);

        $currentUserId = $request->user()->id;

        // Check if 'game_id' is present and is -1, which gets all sessions of all games
        if (isset($validatedData['game_id']) && $validatedData['game_id'] == -1) {
            $playSessions = PlaySession::with('game')
            ->where('user_id', $currentUserId)
            ->get();
        } else {
            // otherwise we query the sessions of the provided game
            // Ensure 'game_id' is present in the request
            if (!isset($validatedData['game_id'])) {
                // Handle the case where 'game_id' is not provided
            }

            $playSessions = PlaySession::with('game')
            ->where([
                ['user_id', '=', $currentUserId],
                ['game_id', '=', $validatedData['game_id']]
            ])
            ->get();
        }

        $groupedByWeek = $playSessions->groupBy(function($session) {
            return Carbon::parse($session->start_session)->startOfWeek()->format('Y-m-d');
        });

        // Sum durations and convert to hours
        $weeklyDurations = $groupedByWeek->map(function($weekSessions) {
            $totalMilliseconds = $weekSessions->sum('duration_milliseconds');
            $hours = $totalMilliseconds / (1000 * 60 * 60);
            return round($hours, 2);
        });

        return $weeklyDurations;
    }

    public function getUserGroupedPlaySessions(Request $request)
    {
        $currentUserId = $request->user()->id;
        
        $playSessions = PlaySession::with('game')
                    ->where('user_id', $currentUserId)
                    ->orderBy('start_session', 'desc')
                    ->get()
                    ->groupBy(function($playSession) {
                        // Group by date part of the start_session
                        return Carbon::parse($playSession->start_session)->format('Y-m-d');
                    });

        return response()->json($playSessions);
    }

    public function getGamePlaySessions(Request $request, $id)
    {
        $gameId = $id;
        $currentUserId = $request->user()->id;

        if ($gameId && $gameId == -1) {
            $playSessions = PlaySession::with('game')
            ->where('user_id', $currentUserId)
            ->orderBy('start_session', 'desc')
            ->get()
            ->groupBy(function($playSession) {
                // Group by date part of the start_session
                return Carbon::parse($playSession->start_session)->format('Y-m-d');
            });
        } else {
            // otherwise we query the sessions of the provided game
            // Ensure 'game_id' is present in the request
            if (!isset($validatedData['game_id'])) {
                // Handle the case where 'game_id' is not provided
            }

            $playSessions = PlaySession::with('game')
                    ->where('user_id', $currentUserId)
                    ->where('game_id', $gameId)
                    ->orderBy('start_session', 'desc')
                    ->get()
                    ->groupBy(function($playSession) {
                        // Group by date part of the start_session
                        return Carbon::parse($playSession->start_session)->format('Y-m-d');
                    });
        }
        
        return response()->json($playSessions);
    }

}
