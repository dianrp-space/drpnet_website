<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'user_id',
        'comment',
        'guest_name',
    ];

    /**
     * Get the gallery that owns the comment.
     */
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    /**
     * Get the user who wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 