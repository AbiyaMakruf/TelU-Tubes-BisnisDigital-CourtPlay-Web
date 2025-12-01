<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Court extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'type',
        'surface',
        'city',
        'address',
        'status',
    ];

    /* -----------------------
       Relationships
    ----------------------- */

    public function searches()
    {
        return $this->hasMany(MatchmakingSearch::class);
    }
}
