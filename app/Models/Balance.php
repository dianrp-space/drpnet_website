<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Balance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'balance'
    ];
    
    /**
     * Get the user that owns the balance
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Add amount to balance
     */
    public function add($amount, $description = null, $paymentMethod = null, $relatedId = null)
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }
        
        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => $this->user_id,
            'type' => 'deposit',
            'amount' => $amount,
            'status' => 'success',
            'description' => $description,
            'payment_method' => $paymentMethod,
            'related_id' => $relatedId
        ]);
        
        // Update balance
        $this->increment('balance', $amount);
        
        return $transaction;
    }
    
    /**
     * Subtract amount from balance
     */
    public function subtract($amount, $description = null, $type = 'purchase', $relatedId = null)
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }
        
        if ($this->balance < $amount) {
            throw new \InvalidArgumentException('Insufficient balance');
        }
        
        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => $this->user_id,
            'type' => $type,
            'amount' => $amount,
            'status' => 'success',
            'description' => $description,
            'related_id' => $relatedId
        ]);
        
        // Update balance
        $this->decrement('balance', $amount);
        
        return $transaction;
    }
    
    /**
     * Transfer amount to another user
     */
    public function transferTo(User $recipient, $amount, $description = null)
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }
        
        if ($this->balance < $amount) {
            throw new \InvalidArgumentException('Insufficient balance');
        }
        
        // Create transfer record
        $transfer = BalanceTransfer::create([
            'from_user_id' => $this->user_id,
            'to_user_id' => $recipient->id,
            'amount' => $amount,
            'status' => 'success',
            'description' => $description
        ]);
        
        // Create transaction records for both users
        $this->subtract($amount, $description ?? "Transfer to {$recipient->name}", 'transfer', $transfer->id);
        
        // Ensure recipient has a balance record
        $recipientBalance = $recipient->balance()->firstOrCreate(['balance' => 0]);
        $recipientBalance->add($amount, $description ?? "Transfer from {$this->user->name}", null, $transfer->id);
        
        return $transfer;
    }
}
