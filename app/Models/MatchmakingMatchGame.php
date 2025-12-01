<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MatchmakingMatchGame extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'matchmaking_match_id',
        'game_number',
        'team1_score',
        'team2_score',
    ];

    /* -----------------------
       Relationships
    ----------------------- */

    public function match()
    {
        return $this->belongsTo(MatchmakingMatch::class, 'matchmaking_match_id');
    }
}
