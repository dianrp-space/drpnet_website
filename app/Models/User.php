<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'role',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Get the purchases made by the user
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    
    /**
     * Get the products purchased by the user
     */
    public function purchasedProducts()
    {
        return $this->belongsToMany(Product::class, 'purchases')
            ->withPivot('price_paid', 'created_at')
            ->withTimestamps();
    }
    
    /**
     * Check if user has purchased a specific product
     */
    public function hasPurchased(Product $product)
    {
        return $this->purchases()->where('product_id', $product->id)->exists();
    }
    
    /**
     * Get the user's balance
     */
    public function balance()
    {
        return $this->hasOne(Balance::class);
    }
    
    /**
     * Get the user's balance amount (creates balance if doesn't exist)
     */
    public function getBalanceAttribute()
    {
        $balance = $this->balance()->firstOrCreate([
            'user_id' => $this->id,
        ], [
            'balance' => 0  // Nilai default hanya digunakan jika record baru dibuat
        ]);
        
        return $balance->balance;
    }
    
    /**
     * Get all transactions of the user
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
    /**
     * Get all transfer transactions from this user
     */
    public function sentTransfers()
    {
        return $this->hasMany(BalanceTransfer::class, 'from_user_id');
    }
    
    /**
     * Get all transfer transactions to this user
     */
    public function receivedTransfers()
    {
        return $this->hasMany(BalanceTransfer::class, 'to_user_id');
    }
    
    /**
     * Get the user's shopping cart
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    
    /**
     * Get or create a cart for this user
     */
    public function getOrCreateCart()
    {
        if (!$this->cart) {
            return $this->cart()->create();
        }
        
        return $this->cart;
    }

    /**
     * Get the profile photo URL or null if not set
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        return null;
    }
}
