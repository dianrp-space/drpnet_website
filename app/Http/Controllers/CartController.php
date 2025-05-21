<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's shopping cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = auth()->user()->getOrCreateCart();
        $cartItems = $cart->items()->with('product')->get();
        
        return view('shop.cart', compact('cart', 'cartItems'));
    }

    /**
     * Add a product to cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Request $request, Product $product)
    {
        // Validate that product is active
        if (!$product->is_active) {
            return redirect()->back()->with('error', 'Produk tidak tersedia.');
        }
        
        $cart = auth()->user()->getOrCreateCart();
        
        // Check if item already exists in cart
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            // Update quantity if already in cart
            $cartItem->update([
                'quantity' => $cartItem->quantity + 1
            ]);
        } else {
            // Add new item to cart
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price
            ]);
        }
        
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update the cart item quantity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        // Verify that the cart item belongs to the authenticated user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }
        
        $cartItem->update([
            'quantity' => $request->quantity
        ]);
        
        return redirect()->route('cart.index')->with('success', 'Kuantitas produk berhasil diupdate.');
    }

    /**
     * Remove the specified cart item from storage.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function removeItem(CartItem $cartItem)
    {
        // Verify that the cart item belongs to the authenticated user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }
        
        $cartItem->delete();
        
        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /**
     * Clear all items from the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function clearCart()
    {
        $cart = auth()->user()->getOrCreateCart();
        $cart->items()->delete();
        
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }
    
    /**
     * Process checkout from cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout()
    {
        $user = auth()->user();
        $cart = $user->getOrCreateCart();
        $cartItems = $cart->items()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }
        
        // Check for duplicate products that are already in pending payment status
        $pendingProducts = [];
        $existingPurchases = [];
        
        foreach ($cartItems as $item) {
            $existingPurchase = $user->purchases()
                ->where('product_id', $item->product_id)
                ->where('payment_status', 'pending')
                ->first();
                
            if ($existingPurchase) {
                $pendingProducts[] = $item->product->name;
                $existingPurchases[] = $existingPurchase->id;
            }
        }
        
        // If we found products with pending payment, redirect to the first one
        if (!empty($pendingProducts)) {
            $message = 'Produk ' . implode(', ', $pendingProducts) . ' sudah ada dalam daftar pembelian Anda dengan status pembayaran masih pending. Silakan selesaikan pembayaran terlebih dahulu.';
            return redirect()->route('shop.payment', $existingPurchases[0])->with('error', $message);
        }
        
        // Here we would typically validate and create purchase records
        // For each item in the cart
        $firstPurchase = null;
        
        foreach ($cartItems as $cartItem) {
            try {
                // Create purchase record
                $purchase = $user->purchases()->create([
                    'product_id' => $cartItem->product_id,
                    'price_paid' => $cartItem->price,
                    'status' => 'completed',
                    'payment_status' => 'pending', // Or implement balance payment
                    'transaction_id' => 'TRX-' . strtoupper(substr(md5(rand()), 0, 8)),
                ]);
                
                if (!$firstPurchase) {
                    $firstPurchase = $purchase;
                }
            } catch (\Exception $e) {
                // If we encounter a duplicate entry error, find the existing purchase
                $existingPurchase = $user->purchases()
                    ->where('product_id', $cartItem->product_id)
                    ->first();
                    
                if ($existingPurchase && !$firstPurchase) {
                    $firstPurchase = $existingPurchase;
                }
            }
        }
        
        // Clear the cart after checkout
        $cart->items()->delete();
        
        // Redirect to payment page for the first purchase
        if ($firstPurchase) {
            return redirect()->route('shop.payment', $firstPurchase)->with('success', 'Checkout berhasil. Silakan selesaikan pembayaran untuk mengunduh produk.');
        } else {
            return redirect()->route('shop.my-purchases')->with('error', 'Terjadi kesalahan saat checkout. Silakan periksa daftar pembelian Anda.');
        }
    }
}
