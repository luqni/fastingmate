<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->paginate(9);
            
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        if (!$post->is_published) {
            abort(404);
        }
        
        // Only increment views if this post hasn't been viewed in this session
        $sessionKey = 'post_viewed_' . $post->id;
        if (!session()->has($sessionKey)) {
            $post->incrementViews();
            session()->put($sessionKey, true);
        }

        return view('posts.show', compact('post'));
    }
}
