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
        $isSandbox = config('services.tripay.sandbox', false); // Standarisasi pengecekan sandbox
        
        $paymentMethodName = $this->getPaymentMethodName($validatedData['payment_method']);
        
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
        
        session(['payment_reference' => $transaction->reference, 'transaction_id' => $transaction->id]);
        
        $apiKey = config('services.tripay.api_key');
        $privateKey = config('services.tripay.private_key');
        $merchantCode = config('services.tripay.merchant_code');
        $apiUrl = config('services.tripay.api_url');
        
        // Validasi konfigurasi Tripay
        if (empty($apiKey) || empty($privateKey) || empty($merchantCode) || empty($apiUrl)) {
            if (!$isSandbox) {
                return redirect()->route('balance.deposit')->with('error', 'Konfigurasi pembayaran (API Key/Merchant Code) belum lengkap. Silakan hubungi admin.');
            }
            // Mode Sandbox: boleh lanjut ke halaman simulasi dengan pesan error
            return redirect()->route('deposit.payment', ['transaction' => $transaction->id])
                ->with('error', 'Sandbox Mode: Konfigurasi Tripay tidak lengkap. Anda dapat melanjutkan dengan simulasi.');
        }
        
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
            $responseBody = curl_exec($curl);
            $curlError = curl_error($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            
            if ($curlError) {
                \Log::error('Tripay API cURL Error: ' . $curlError);
                if (!$isSandbox) {
                    return redirect()->route('balance.deposit')
                        ->with('error', 'Gagal terhubung ke payment gateway. Silakan coba lagi atau hubungi admin.');
                }
                return redirect()->route('deposit.payment', ['transaction' => $transaction->id])
                    ->with('error', 'Sandbox Mode: Gagal terhubung ke Tripay API. ' . $curlError . '. Anda dapat melanjutkan simulasi.');
            }
            
            $response = json_decode($responseBody, true);
            
            if (isset($response['success']) && $response['success'] === true && isset($response['data']['checkout_url'])) {
                $transaction->update([
                    'tripay_url' => $response['data']['checkout_url'],
                    'tripay_reference' => $response['data']['reference'] ?? null,
                ]);
                return redirect($response['data']['checkout_url']);
            } else {
                $tripayMessage = $response['message'] ?? 'Error tidak diketahui dari Tripay.';
                if ($httpCode === 401) {
                    $tripayMessage = 'API Key Tripay tidak valid atau belum diatur.';
                }
                \Log::error('Tripay API Response Error: ' . $tripayMessage, ['response' => $response, 'http_code' => $httpCode]);
                if (!$isSandbox) {
                    return redirect()->route('balance.deposit')
                        ->with('error', 'Gagal membuat transaksi dengan payment gateway: ' . $tripayMessage . '. Silakan hubungi admin.');
                }
                return redirect()->route('deposit.payment', ['transaction' => $transaction->id])
                    ->with('error', 'Sandbox Mode: Tripay API Error - ' . $tripayMessage . '. Anda dapat melanjutkan simulasi.');
            }
        } catch (\Exception $e) {
            \Log::error('Tripay API Exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            if (!$isSandbox) {
                return redirect()->route('balance.deposit')
                    ->with('error', 'Terjadi kesalahan teknis saat memproses pembayaran. Silakan hubungi admin.');
            }
            return redirect()->route('deposit.payment', ['transaction' => $transaction->id])
                ->with('error', 'Sandbox Mode: Exception - ' . $e->getMessage() . '. Anda dapat melanjutkan simulasi.');
        }
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
        // Proteksi: hanya boleh di sandbox, menggunakan config services
        if (!config('services.tripay.sandbox', false)) {
            abort(403, 'Fitur simulasi pembayaran sudah dinonaktifkan di mode produksi.');
        }
        
        // Nonaktifkan endpoint simulasi di produksi
        abort(403, 'Fitur simulasi pembayaran sudah dinonaktifkan di produksi.');
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
