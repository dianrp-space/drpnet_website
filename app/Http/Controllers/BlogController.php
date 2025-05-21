<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    /**
     * Display a listing of published blog posts
     */
    public function index()
    {
        $posts = Post::where('status', 'publish')
            ->with('category', 'user', 'tags')
            ->withCount('comments')
            ->orderBy('published_at', 'desc')
            ->paginate(10);
            
        $categories = Category::withCount('posts')->get();
        $tags = Tag::all();
        
        return view('blog.index', compact('posts', 'categories', 'tags'));
    }
    
    /**
     * Display the specified blog post
     */
    public function show($slug)
    {
        $post = Cache::remember('post.' . $slug, 3600, function () use ($slug) {
            return Post::with(['author', 'category', 'tags', 'comments'])
                ->where('slug', $slug)
                ->firstOrFail();
        });

        $relatedPosts = Cache::remember('related_posts.' . $slug, 3600, function () use ($post) {
            return $post->getRelatedPosts();
        });

        return view('blog.show', compact('post', 'relatedPosts'));
    }
    
    /**
     * Display posts by category
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $posts = Post::where('category_id', $category->id)
            ->where('status', 'publish')
            ->with('category', 'user', 'tags')
            ->withCount('comments')
            ->orderBy('published_at', 'desc')
            ->paginate(10);
            
        $categories = Category::withCount('posts')->get();
        $tags = Tag::all();
        
        return view('blog.category', compact('posts', 'category', 'categories', 'tags'));
    }
    
    /**
     * Display posts by tag
     */
    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        
        $posts = $tag->posts()
            ->where('status', 'publish')
            ->with('category', 'user', 'tags')
            ->withCount('comments')
            ->orderBy('published_at', 'desc')
            ->paginate(10);
            
        $categories = Category::withCount('posts')->get();
        $tags = Tag::all();
        
        return view('blog.tag', compact('posts', 'tag', 'categories', 'tags'));
    }
} 