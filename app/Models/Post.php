<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'content', 'status', 'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    
    /**
     * Get the comments for the blog post.
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function getRelatedPosts($limit = 3)
    {
        return Post::where('id', '!=', $this->id)
            ->whereHas('tags', function($query) {
                $query->whereIn('tags.id', $this->tags->pluck('id'));
            })
            ->orWhere('category_id', $this->category_id)
            ->latest()
            ->take($limit)
            ->get();
    }
}
