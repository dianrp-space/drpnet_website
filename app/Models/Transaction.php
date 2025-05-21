<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
        'description',
        'payment_method',
        'related_id',
        'reference'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];
    
    /**
     * Get the user that owns the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope a query to only include deposit transactions.
     */
    public function scopeDeposits($query)
    {
        return $query->where('type', 'deposit');
    }
    
    /**
     * Scope a query to only include purchase transactions.
     */
    public function scopePurchases($query)
    {
        return $query->where('type', 'purchase');
    }
    
    /**
     * Scope a query to only include transfer transactions.
     */
    public function scopeTransfers($query)
    {
        return $query->where('type', 'transfer');
    }
    
    /**
     * Scope a query to only include successful transactions.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }
    
    /**
     * Scope a query to only include pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope a query to only include failed transactions.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
    
    /**
     * Get the related transfer if this transaction is a transfer.
     */
    public function transfer()
    {
        if ($this->type == 'transfer') {
            return $this->belongsTo(BalanceTransfer::class, 'related_id');
        }
        return null;
    }
    
    /**
     * Get the related purchase if this transaction is a purchase.
     */
    public function purchase()
    {
        if ($this->type == 'purchase') {
            return $this->belongsTo(Purchase::class, 'related_id');
        }
        return null;
    }
}
