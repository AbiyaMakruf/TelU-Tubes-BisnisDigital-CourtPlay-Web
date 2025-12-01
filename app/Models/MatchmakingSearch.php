<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MatchmakingSearch extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'court_id',
        'play_mode',
        'play_date',
        'play_time_start',
        'play_time_end',
        'status',
    ];

    /* -----------------------
       Relationships
    ----------------------- */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function matchPlayers()
    {
        return $this->hasMany(MatchmakingMatchPlayer::class, 'from_search_id');
    }
}
