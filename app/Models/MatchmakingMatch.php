<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MatchmakingMatch extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'mode',
        'status',
    ];

    /* -----------------------
       Relationships
    ----------------------- */

    public function players()
    {
        return $this->hasMany(MatchmakingMatchPlayer::class);
    }

    public function team1Players()
    {
        return $this->players()->where('team', 1);
    }

    public function team2Players()
    {
        return $this->players()->where('team', 2);
    }

    public function games()
    {
        return $this->hasMany(MatchmakingMatchGame::class);
    }
}
