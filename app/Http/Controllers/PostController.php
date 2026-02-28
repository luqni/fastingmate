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

        if ($request->ajax()) {
            $view = '';
            foreach ($posts as $post) {
                $view .= view('posts.partials.post-card', compact('post'))->render();
            }
            return response()->json([
                'html' => $view,
                'next_page_url' => $posts->nextPageUrl()
            ]);
        }
            
        // Get Tadabbur for Dynamic Island
        $tadabbur = null;
        if (auth()->check()) {
            $tadabbur = app(\App\Services\TadabburService::class)->getTodayTadabbur(auth()->user());
        } else {
            // specific logic for guest: random quran source
            $source = \App\Models\QuranSource::inRandomOrder()->first();
            if ($source) {
                $tadabbur = new \stdClass();
                $tadabbur->status = 'guest'; // specific status for view logic
                $tadabbur->quranSource = $source;
                $tadabbur->reflection = null;
            }
        }

        return view('posts.index', compact('posts', 'featuredPost', 'trendingPosts', 'tadabbur'));
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

        // Get Tadabbur for Dynamic Island
        $tadabbur = null;
        if (auth()->check()) {
            $tadabbur = app(\App\Services\TadabburService::class)->getTodayTadabbur(auth()->user());
        } else {
             $source = \App\Models\QuranSource::inRandomOrder()->first();
            if ($source) {
                $tadabbur = new \stdClass();
                $tadabbur->status = 'guest';
                $tadabbur->quranSource = $source;
                $tadabbur->reflection = null;
            }
        }

        // Get Related Posts
        $relatedPosts = Post::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $post->load(['comments.user' => function ($query) {
            $query->select('id', 'name', 'avatar');
        }, 'comments' => function ($query) {
            $query->latest();
        }]);

        $likesCount = $post->likes()->count();
        $hasLiked = $post->likes()->where('session_id', session()->getId())->exists();

        return view('posts.show', compact('post', 'tadabbur', 'relatedPosts', 'likesCount', 'hasLiked'));
    }

    public function share(Request $request, Post $post)
    {
        $validated = $request->validate([
            'platform' => 'required|string',
        ]);

        \App\Models\PostShare::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'platform' => $validated['platform'],
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['status' => 'success']);
    }

    public function like(Request $request, Post $post)
    {
        $sessionId = session()->getId();
        $ipAddress = $request->ip();

        $existingLike = $post->likes()->where('session_id', $sessionId)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $post->likes()->create([
                'session_id' => $sessionId,
                'ip_address' => $ipAddress,
            ]);
            $liked = true;
        }

        return response()->json([
            'status' => 'success',
            'liked' => $liked,
            'likes_count' => $post->likes()->count()
        ]);
    }

    public function comment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            // Sanitize content by stripping tags completely
            'content' => strip_tags($validated['content'])
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
