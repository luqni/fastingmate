<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // Create a base query for published posts
        $baseQuery = Post::where('is_published', true)
            ->whereNotNull('published_at');

        $featuredPost = null;
        $trendingPosts = collect();

        // Only fetch featured/trending and apply exclusions if NOT searching
        if (!$request->filled('search')) {
            // Get the featured post (most viewed)
            $featuredPost = (clone $baseQuery)
                ->orderBy('views_count', 'desc')
                ->first();

            // Get trending posts (next 4 most viewed), excluding the featured one
            if ($featuredPost) {
                $trendingPosts = (clone $baseQuery)
                    ->where('id', '!=', $featuredPost->id)
                    ->orderBy('views_count', 'desc')
                    ->limit(4)
                    ->get();
            }

            // Exclude these from the main list so they aren't duplicated
            $excludeIds = collect([$featuredPost?->id])->merge($trendingPosts->pluck('id'))->filter()->toArray();
            
            $postsQuery = (clone $baseQuery)
                ->whereNotIn('id', $excludeIds);
        } else {
            // If searching, search EVERYTHING (don't exclude featured/trending)
            $postsQuery = (clone $baseQuery);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            // Use whereRaw for reliable case-inequality across all DB drivers
            $postsQuery->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
        }

        $posts = $postsQuery->latest('published_at')
            ->paginate(9)
            ->withQueryString();
            
        return view('posts.index', compact('posts', 'featuredPost', 'trendingPosts'));
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
