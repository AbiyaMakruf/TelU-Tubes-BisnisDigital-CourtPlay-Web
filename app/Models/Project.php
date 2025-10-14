<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // PENTING: Impor Trait HasUuids

class Project extends Model
{
    use HasFactory, HasUuids; // PENTING: Gunakan Trait HasUuids

    protected $table = 'projects';

    // Properti ini diwajibkan untuk UUID Primary Keys
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'project_details_id',
        'project_name',
        'is_mailed',
        'upload_date',
        'link_image_thumbnail',

    ];

    protected $casts = [
        'upload_date' => 'datetime',
        'is_mailed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function projectDetails()
    {
        return $this->belongsTo(ProjectDetail::class, 'project_details_id', 'id');
    }
}
