<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;


    public function playSessions()
    {
        return $this->hasMany(PlaySession::class);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'coverart',
        'playtime',
        'estimated_playtime',
        'user_id',
        'console'
    ];
}
