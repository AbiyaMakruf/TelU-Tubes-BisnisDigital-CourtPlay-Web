<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // PENTING: Impor Trait HasUuids

class ProjectDetail extends Model
{
    use HasFactory, HasUuids; // PENTING: Gunakan Trait HasUuids

    protected $table = 'project_details';

    // Properti ini diwarisi dari HasUuids, tetapi baik untuk disetel secara eksplisit
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'description',
        'link_original_video',
        'link_video_object_detection',
        'link_video_keypoints',
        'forehand_count',
        'backhand_count',
        'serve_count',
        'link_images_ball_droppings',
        'video_duration',
        'video_processing_time',
    ];

    /**
     * Relasi: ProjectDetails belongs to Project (kebalikan dari relasi di Project model).
     */
    public function project()
    {
        // Relasi ini mungkin perlu disesuaikan tergantung bagaimana Anda mendefinisikan foreign key
        return $this->hasOne(Project::class, 'project_details_id', 'id');
    }
}
