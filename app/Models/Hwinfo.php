<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Hwinfo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'hwinfo';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'project_id',
        'user_id',
        'detection_inference_time',
        'keypoint_inference_time',
        'total_inference_time',
        'gpu_name',
        'vram_mb',
        'cpu_name',
        'cpu_threads',
        'ram_mb',
        'os_info',
        'is_success',
    ];

    protected $casts = [
        'is_success' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
