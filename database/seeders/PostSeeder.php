<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'title' => 'What tennis level are you?',
                'cover_url' => 'https://storage.googleapis.com/courtplay-storage/assets/Web/dump-news.png',
                'content' => <<<HTML
                    <h2>The Unseen Opponent</h2>
                    <p>AI is reshaping match analysis and preparation…</p>
                    <ul><li>Shot pattern recognition</li><li>Energy mapping</li><li>Weakness identification</li></ul>
                HTML,
            ],
            [
                'title' => 'AI in Post-Match Analytics: A Coach’s Best Friend',
                'cover_url' => 'https://storage.googleapis.com/courtplay-storage/assets/Web/dump-news.png',
                'content' => '<p>From hours of manual review to instant insights powered by models…</p>',
            ],
            [
                'title' => 'From Raw Footage to Insights in Minutes',
                'cover_url' => 'https://storage.googleapis.com/courtplay-storage/assets/Web/dump-news.png',
                'content' => '<p>Pipeline desain CourtPlay mempersingkat proses analisis.</p>',
            ],
            [
                'title' => 'How Pros Use Heatmaps to Train Smarter',
                'cover_url' => 'https://storage.googleapis.com/courtplay-storage/assets/Web/dump-news.png',
                'content' => '<p>Heatmap menyorot coverage, fatigue zones, dan momentum break.</p>',
            ],
            [
                'title' => 'Building Consistency: Drills Backhand Modern',
                'cover_url' => 'https://storage.googleapis.com/courtplay-storage/assets/Web/dump-news.png',
                'content' => '<p>Rangkaian drill untuk stabilitas backhand dengan metrik terukur.</p>',
            ],
        ];

        foreach ($rows as $i => $r) {
            $title = $r['title'];
            $slug  = Str::slug($title);

            // pastikan slug unik jika seeder dijalankan lagi
            $base = $slug; $n = 2;
            while (Post::where('slug', $slug)->exists()) {
                $slug = "{$base}-{$n}";
                $n++;
            }

            Post::create([
                'title'         => $title,
                'slug'          => $slug,
                'excerpt'       => Str::limit(strip_tags($r['content']), 180),
                'content'       => $r['content'],
                'cover_url'     => $r['cover_url'],
                'is_published'  => true,
                'published_at'  => Carbon::now()->subDays(5 - $i),
                'views'         => rand(50, 500),
            ]);
        }
    }
}
