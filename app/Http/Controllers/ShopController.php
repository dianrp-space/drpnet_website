<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * Display a listing of active products
     */
    public function index()
    {
        $products = Product::where('is_active', true)
            ->latest()
            ->paginate(12);
            
        return view('shop.index', compact('products'));
    }
    
    /**
     * Display product details
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }
        
        // Check if user has already purchased this product
        $purchased = auth()->check() && auth()->user()->hasPurchased($product);
        $pendingPayment = false;
        
        if (auth()->check()) {
            $purchase = Purchase::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->first();
                
            $pendingPayment = $purchase && $purchase->payment_status === 'pending';
        }
        
        return view('shop.show', compact('product', 'purchased', 'pendingPayment'));
    }
    
    /**
     * Purchase product - Initialize the purchase process
     */
    public function purchase(Product $product)
    {
        // Ensure product is active
        if (!$product->is_active) {
            abort(404);
        }
        
        $user = auth()->user();
        
        // Check if user has already purchased this product
        $existingPurchase = Purchase::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();
            
        if ($existingPurchase) {
            if ($existingPurchase->payment_status === 'completed') {
                return redirect()->route('shop.download', $product)
                    ->with('info', 'You have already purchased this product.');
            }
            
            // If payment is pending, redirect to payment page
            return redirect()->route('shop.payment', $existingPurchase);
        }
        
        // Create the purchase record with pending status
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'price_paid' => $product->price,
            'status' => 'completed',
            'payment_status' => 'pending',
            'transaction_id' => 'TRX-' . Str::upper(Str::random(8)),
        ]);
        
        // Redirect to payment page
        return redirect()->route('shop.payment', $purchase);
    }
    
    /**
     * Display payment page
     */
    public function payment(Purchase $purchase)
    {
        // Verify that the purchase belongs to the authenticated user
        if ($purchase->user_id !== auth()->id()) {
            abort(403);
        }
        
        // If payment is already completed, redirect to download
        if ($purchase->payment_status === 'completed') {
            return redirect()->route('shop.download', $purchase->product)
                ->with('success', 'Your payment has been processed. You can now download your product.');
        }
        
        return view('shop.payment', compact('purchase'));
    }
    
    /**
     * Process payment (simulation)
     */
    public function processPayment(Request $request, Purchase $purchase)
    {
        // Verify that the purchase belongs to the authenticated user
        if ($purchase->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Validate payment details
        $request->validate([
            'payment_method' => 'required|string',
        ]);
        
        $user = auth()->user();
        $paymentMethod = $request->payment_method;
        
        // Check if using balance payment
        if ($paymentMethod === 'BALANCE') {
            // Check if user has enough balance
            if ($user->balance < $purchase->price_paid) {
                return redirect()->back()->with('error', 'Saldo tidak cukup. Silakan isi saldo atau gunakan metode pembayaran lain.');
            }
            
            try {
                // Get the balance model
                $balance = $user->balance()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);
                
                // Subtract from balance
                $balance->subtract(
                    $purchase->price_paid, 
                    "Pembelian produk: {$purchase->product->name}",
                    'purchase',
                    $purchase->id
                );
                
                // Update purchase status
                $purchase->update([
                    'payment_status' => 'completed',
                    'payment_method' => 'balance',
                ]);
                
                return redirect()->route('shop.payment-success', $purchase)
                    ->with('success', 'Pembayaran berhasil! Produk telah dibeli menggunakan saldo.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
        
        // For Tripay payment methods
        // In a real app, you would integrate with Tripay payment gateway here
        
        // Untuk implementasi Tripay
        $apiKey = config('services.tripay.api_key');
        $privateKey = config('services.tripay.private_key');
        $merchantCode = config('services.tripay.merchant_code');
        $apiUrl = config('services.tripay.api_url');
        
        $data = [
            'method'         => $request->payment_method,
            'merchant_ref'   => $purchase->transaction_id ?? 'TRX-'.time(),
            'amount'         => $purchase->price_paid,
            'customer_name'  => auth()->user()->name,
            'customer_email' => auth()->user()->email,
            'customer_phone' => auth()->user()->phone ?? '08123456789',
            'order_items'    => [
                [
                    'name'        => $purchase->product->name,
                    'price'       => $purchase->price_paid,
                    'quantity'    => 1,
                    'product_url' => route('shop.show', $purchase->product)
                ]
            ],
            'return_url'   => route('shop.payment-success', $purchase),
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => hash_hmac('sha256', $merchantCode.$purchase->transaction_id.$purchase->price_paid, $privateKey)
        ];
        
        try {
            // Make request to Tripay API to create transaction
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_FRESH_CONNECT  => true,
                CURLOPT_URL            => $apiUrl . '/transaction/create',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
                CURLOPT_FAILONERROR    => false,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => http_build_query($data),
                CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
            ]);
            
            $response = curl_exec($curl);
            $error = curl_error($curl);
            
            curl_close($curl);
            
            if ($error) {
                // Log the error
                \Log::error('Tripay API Error: ' . $error);
                
                // Fallback to simulation
                $purchase->update([
                    'payment_status' => 'completed',
                    'payment_method' => $request->payment_method,
                ]);
                
                return redirect()->route('shop.payment-success', $purchase)
                    ->with('error', 'Gagal terhubung ke payment gateway. Pembayaran dilewati untuk demo.');
            }
            
            $response = json_decode($response, true);
            
            // Check if response is valid
            if (isset($response['success']) && $response['success'] === true && isset($response['data']['checkout_url'])) {
                // Update purchase status with Tripay reference
                $purchase->update([
                    'payment_method' => $request->payment_method,
                    'transaction_id' => $response['data']['reference'] ?? $purchase->transaction_id,
                ]);
                
                // Create a transaction record
                $transaction = \App\Models\Transaction::create([
                    'user_id' => auth()->id(),
                    'type' => 'purchase',
                    'amount' => $purchase->price_paid, 
                    'status' => 'pending',
                    'description' => "Pembelian produk: {$purchase->product->name}",
                    'payment_method' => $request->payment_method,
                    'related_id' => $purchase->id,
                    'reference' => $purchase->transaction_id ?? 'TRX-'.time(),
                    'tripay_url' => $response['data']['checkout_url'],
                    'tripay_reference' => $response['data']['reference'] ?? null,
                ]);
                
                // Redirect to Tripay checkout page
                return redirect($response['data']['checkout_url']);
            } else {
                // Log the error response
                \Log::error('Tripay API Response Error', $response);
                
                // Fallback to simulation for now
                $purchase->update([
                    'payment_status' => 'completed',
                    'payment_method' => $request->payment_method,
                ]);
                
                return redirect()->route('shop.payment-success', $purchase)
                    ->with('error', 'Gagal membuat transaksi pembayaran. ' . ($response['message'] ?? 'Pembayaran dilewati untuk demo.'));
            }
        } catch (\Exception $e) {
            // Log exception
            \Log::error('Tripay API Exception: ' . $e->getMessage());
            
            // Fallback to simulation
            $purchase->update([
                'payment_status' => 'completed',
                'payment_method' => $request->payment_method,
            ]);
            
            return redirect()->route('shop.payment-success', $purchase)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage() . '. Pembayaran dilewati untuk demo.');
        }
    }
    
    /**
     * Show payment success page
     */
    public function paymentSuccess(Purchase $purchase)
    {
        // Verify that the purchase belongs to the authenticated user
        if ($purchase->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Verify that the payment is completed
        if ($purchase->payment_status !== 'completed') {
            return redirect()->route('shop.payment', $purchase)
                ->with('error', 'Pembayaran belum selesai.');
        }
        
        return view('shop.payment-success', compact('purchase'));
    }
    
    /**
     * Download product (protected by auth and payment verification)
     */
    public function download(Product $product)
    {
        $user = auth()->user();
        
        // Check if user has purchased the product
        $purchase = Purchase::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();
            
        if (!$purchase) {
            return redirect()->route('shop.show', $product)
                ->with('error', 'You need to purchase this product before downloading.');
        }
        
        // Check if payment is completed
        if ($purchase->payment_status !== 'completed') {
            return redirect()->route('shop.payment', $purchase)
                ->with('error', 'Please complete your payment before downloading.');
        }
        
        return Storage::disk('public')->download($product->file_path, $product->name . '.zip');
    }
    
    /**
     * Show user's purchased products
     */
    public function myPurchases()
    {
        $purchases = auth()->user()->purchases()->with('product')->latest()->paginate(10);
        return view('shop.my-purchases', compact('purchases'));
    }
    
    /**
     * Cancel a pending payment
     */
    public function cancelPayment(Purchase $purchase)
    {
        // Verify that the purchase belongs to the authenticated user
        if ($purchase->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Only allow cancellation of pending payments
        if ($purchase->payment_status !== 'pending') {
            return redirect()->route('shop.my-purchases')
                ->with('error', 'Hanya pembayaran dengan status pending yang dapat dibatalkan.');
        }
        
        // Delete the purchase record
        $purchase->delete();
        
        return redirect()->route('shop.my-purchases')
            ->with('success', 'Pembayaran berhasil dibatalkan.');
    }
} 