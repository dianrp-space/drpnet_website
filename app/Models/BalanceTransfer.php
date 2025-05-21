<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BalanceTransfer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'amount',
        'status',
        'description'
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
     * Get the user who sent the transfer
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the user who received the transfer
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
    
    /**
     * Get the transactions related to this transfer
     */
    public function transactions()
    {
        return Transaction::where('related_id', $this->id)
                          ->where('type', 'transfer');
    }
    
    /**
     * Scope a query to only include successful transfers.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }
    
    /**
     * Scope a query to only include pending transfers.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope a query to only include failed transfers.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
