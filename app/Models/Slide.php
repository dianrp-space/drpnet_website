<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'active',
        'order'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Scope a query to only include active slides.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Get all active slides ordered by position
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveSlides()
    {
        return self::active()->orderBy('order')->get();
    }
} 