<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'price',
        'is_active',
        'file_path',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(ProductFile::class);
    }
    
    /**
     * Get the purchases for this product
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    
    /**
     * Get users who purchased this product
     */
    public function purchasedBy()
    {
        return $this->belongsToMany(User::class, 'purchases')
            ->withPivot('price_paid', 'created_at')
            ->withTimestamps();
    }
}
