<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MatchmakingMatchPlayer extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'matchmaking_match_id',
        'user_id',
        'team',
        'from_search_id',
    ];

    /* -----------------------
       Relationships
    ----------------------- */

    public function match()
    {
        return $this->belongsTo(MatchmakingMatch::class, 'matchmaking_match_id');
    }

    public function search()
    {
        return $this->belongsTo(MatchmakingSearch::class, 'from_search_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
