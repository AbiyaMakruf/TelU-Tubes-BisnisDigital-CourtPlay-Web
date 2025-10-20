<?php

// app/Models/Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','slug','excerpt','content','cover_url',
        'is_published','published_at','views',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            // auto-slug jika kosong
            if (blank($post->slug) && filled($post->title)) {
                $base = Str::slug(Str::limit($post->title, 80, ''));
                $slug = $base; $i = 2;
                while (static::where('slug',$slug)
                        ->when($post->exists, fn($q)=>$q->where('id','!=',$post->id))
                        ->exists()) {
                    $slug = "{$base}-{$i}"; $i++;
                }
                $post->slug = $slug;
            }
            // excerpt otomatis dari content jika belum diisi
            if (blank($post->excerpt) && filled($post->content)) {
                $post->excerpt = Str::limit(trim(strip_tags($post->content)), 200);
            }
        });
    }

    /* Scopes */
    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true)
                 ->whereNotNull('published_at')
                 ->where('published_at','<=', now())
                 ->orderByDesc('published_at');
    }

    /* Helpers */
    public function getUrlAttribute(): string
    {
        return route('news.show', $this->slug);
    }

    public function bumpViews(int $by = 1): void
    {
        $this->increment('views', $by);
    }
}
