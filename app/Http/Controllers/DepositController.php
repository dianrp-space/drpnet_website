<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\WhatsAppService;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    /**
     * Show the deposit form.
     */
    public function showForm()
    {
        $user = Auth::user();
        $balance = $user->balance()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);
        
        // Metode pembayaran Tripay
        $paymentMethods = [
            'virtual_account' => [
                'MYBVA' => 'Maybank Virtual Account',
                'PERMATAVA' => 'Permata Virtual Account',
                'BRIVA' => 'BRI Virtual Account',
                'MANDIRIVA' => 'Mandiri Virtual Account',
                'BCAVA' => 'BCA Virtual Account',
                'BNIVA' => 'BNI Virtual Account',
            ],
            'ewallet' => [
                'QRIS' => 'QRIS (OVO/Dana/Gopay/ShopeePay/LinkAja)',
                'OVO' => 'OVO',
                'DANA' => 'DANA',
                'SHOPEEPAY' => 'ShopeePay',
                'LINKAJA' => 'LinkAja',
            ],
            'retail' => [
                'ALFAMART' => 'Alfamart',
                'INDOMARET' => 'Indomaret',
            ]
        ];
        
        return view('balance.deposit', compact('balance', 'paymentMethods'));
    }
    
    /**
     * Process the deposit request and redirect to payment gateway.
     */
    public function process(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:10000', // Minimum deposit 10k
            'payment_method' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        // Mendapatkan nama metode pembayaran untuk deskripsi
        $paymentMethodName = $this->getPaymentMethodName($validatedData['payment_method']);
        
        // Create a pending deposit transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $validatedData['amount'],
            'status' => 'pending',
            'description' => 'Deposit via ' . $paymentMethodName,
            'payment_method' => $validatedData['payment_method'],
            'related_id' => null,
            'reference' => 'DEP-' . Str::upper(Str::random(8))
        ]);
        
        // Store reference in session for callback verification
        session(['payment_reference' => $transaction->reference, 'transaction_id' => $transaction->id]);
        
        // Get Tripay API credentials from config
        $apiKey = config('services.tripay.api_key');
        $privateKey = config('services.tripay.private_key');
        $merchantCode = config('services.tripay.merchant_code');
        $apiUrl = config('services.tripay.api_url');
        
        $data = [
            'method'         => $validatedData['payment_method'],
            'merchant_ref'   => $transaction->reference,
            'amount'         => $validatedData['amount'],
            'customer_name'  => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone ?? '08123456789',
            'order_items'    => [
                [
                    'name'     => 'Deposit Saldo',
                    'price'    => $validatedData['amount'],
                    'quantity' => 1
                ]
            ],
            'return_url'   => route('balance.index'),
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => hash_hmac('sha256', $merchantCode.$transaction->reference.$validatedData['amount'], $privateKey)
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
                
                // For now, fallback to simulation
                return redirect()->route('deposit.payment', ['transaction' => $transaction->id])
                    ->with('error', 'Gagal terhubung ke payment gateway. Mencoba simulasi lokal.');
            }
            
            $response = json_decode($response, true);
            
            // Check if response is valid
            if (isset($response['success']) && $response['success'] === true && isset($response['data']['checkout_url'])) {
                // Store the payment URL in the transaction
                $transaction->update([
                    'tripay_url' => $response['data']['checkout_url'],
                    'tripay_reference' => $response['data']['reference'] ?? null,
                ]);
                
                // Redirect to Tripay checkout page
                return redirect($response['data']['checkout_url']);
            } else {
                // Log the error response
                \Log::error('Tripay API Response Error', $response);
                
                // Fallback to simulation
                return redirect()->route('deposit.payment', ['transaction' => $transaction->id])
                    ->with('error', 'Gagal membuat transaksi. ' . ($response['message'] ?? 'Mencoba simulasi lokal.'));
            }
        } catch (\Exception $e) {
            // Log exception
            \Log::error('Tripay API Exception: ' . $e->getMessage());
            
            // Fallback to simulation for now
            return redirect()->route('deposit.payment', ['transaction' => $transaction->id])
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage() . '. Mencoba simulasi lokal.');
        }
        
        // Fallback to simulation if all else fails
        // return redirect()->route('deposit.payment', ['transaction' => $transaction->id]);
    }
    
    /**
     * Get payment method name based on code
     */
    private function getPaymentMethodName($code)
    {
        $methods = [
            // Virtual Account
            'MYBVA' => 'Maybank Virtual Account',
            'PERMATAVA' => 'Permata Virtual Account',
            'BRIVA' => 'BRI Virtual Account',
            'MANDIRIVA' => 'Mandiri Virtual Account',
            'BCAVA' => 'BCA Virtual Account',
            'BNIVA' => 'BNI Virtual Account',
            
            // E-Wallet
            'QRIS' => 'QRIS',
            'OVO' => 'OVO',
            'DANA' => 'DANA',
            'SHOPEEPAY' => 'ShopeePay',
            'LINKAJA' => 'LinkAja',
            
            // Retail
            'ALFAMART' => 'Alfamart',
            'INDOMARET' => 'Indomaret',
        ];
        
        return $methods[$code] ?? $code;
    }
    
    /**
     * Display payment page (simulation for Tripay).
     */
    public function showPayment($transaction)
    {
        $transaction = Transaction::findOrFail($transaction);
        
        // Check if transaction belongs to user
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if already paid
        if ($transaction->status === 'success') {
            return redirect()->route('balance.index')
                    ->with('success', 'Your deposit has already been processed.');
        }
        
        return view('balance.payment', compact('transaction'));
    }
    
    /**
     * Simulate payment completion (this would be a callback from Tripay in production).
     */
    public function complete(Request $request, $transaction)
    {
        $transaction = Transaction::findOrFail($transaction);
        
        // Check if transaction belongs to user
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if transaction is already processed
        if ($transaction->status === 'success') {
            return redirect()->route('balance.index')
                    ->with('success', 'Your deposit has already been processed.');
        }
        
        try {
            // Use transaction to ensure data consistency
            DB::beginTransaction();
            
            // Get user with fresh data
            $user = User::find(Auth::id());
            
            // Get or create balance record properly
            $balance = $user->balance()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);
            
            // Update balance directly without creating another transaction
            $balance->increment('balance', $transaction->amount);
            
            // Update transaction status
            $transaction->update(['status' => 'success']);
            
            DB::commit();
            
            // Send WhatsApp notification if enabled and user has phone number
            if (!empty($user->phone)) {
                $whatsAppService = app(WhatsAppService::class);
                $whatsAppService->sendDepositNotification($user, $transaction->amount, $balance->balance);
            }
            
            return redirect()->route('balance.index')
                    ->with('success', 'Your deposit of ' . number_format($transaction->amount, 2) . ' has been completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Deposit completion failed: ' . $e->getMessage());
            
            return redirect()->route('balance.index')
                    ->with('error', 'Failed to process your deposit. Please try again or contact support.');
        }
    }
    
    /**
     * Cancel deposit process.
     */
    public function cancel($transaction)
    {
        $transaction = Transaction::findOrFail($transaction);
        
        // Check if transaction belongs to user
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if already completed
        if ($transaction->status === 'success') {
            return redirect()->route('balance.index')
                    ->with('error', 'This deposit has already been processed and cannot be cancelled.');
        }
        
        // Mark as failed
        $transaction->update(['status' => 'failed']);
        
        return redirect()->route('balance.index')
                ->with('info', 'Your deposit has been cancelled.');
    }
}
