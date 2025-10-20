<?php
namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $featured = Post::published()->first();
        $posts    = Post::published()->paginate(10);

        return view('news.index', compact('featured','posts'));
    }

    public function show(string $slug)
    {
        $post = Post::where('slug',$slug)->firstOrFail();
        $post->bumpViews();

        return view('news.show', compact('post'));
    }
}
