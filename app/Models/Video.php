<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'video_original',
        'video_keypoint',
        'video_analytics',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
