<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaySession extends Model
{
    use HasFactory;


    // Relationships
    public function game() 
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'game_id',
        'user_id',
        'is_active',
        'is_paused',
        'notes',
        'start_session',
        'pause_session',
        'play_session',
        'end_session',
        'duration_milliseconds',
        'duration_humanized'
    ];
}
