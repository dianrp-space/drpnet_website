<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'product_id',
        'price_paid',
        'payment_method',
        'transaction_id',
        'status',
        'payment_status',
    ];
    
    /**
     * Get the user who made the purchase
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the product that was purchased
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Check if payment is completed
     */
    public function isPaymentCompleted()
    {
        return $this->payment_status === 'completed';
    }
    
    /**
     * Check if payment is pending
     */
    public function isPaymentPending()
    {
        return $this->payment_status === 'pending';
    }
}
