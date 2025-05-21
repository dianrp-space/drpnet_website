<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Handle Tripay payment callback.
     * This will be called by Tripay when payment status changes.
     */
    public function callback(Request $request)
    {
        // In production, validate the request signature from Tripay
        // For now, we'll just log the request
        Log::info('Payment callback received', $request->all());
        
        $merchantRef = $request->input('merchant_ref');
        $status = $request->input('status');
        
        // Find the transaction by reference
        $transaction = Transaction::where('reference', $merchantRef)->first();
        
        if (!$transaction) {
            Log::error('Transaction not found for reference: ' . $merchantRef);
            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }
        
        // Update transaction status based on Tripay callback
        switch ($status) {
            case 'PAID':
                // Payment successful
                $transaction->update(['status' => 'success']);
                
                // Add balance to user if this is a deposit
                if ($transaction->type === 'deposit') {
                    $user = $transaction->user;
                    $balance = $user->balance()->firstOrCreate(['balance' => 0]);
                    $balance->increment('balance', $transaction->amount);
                }
                break;
                
            case 'EXPIRED':
            case 'FAILED':
                // Payment failed
                $transaction->update(['status' => 'failed']);
                break;
                
            case 'PENDING':
                // Payment still pending (no action needed)
                break;
                
            default:
                Log::warning('Unknown status received: ' . $status);
                break;
        }
        
        // Return success response to Tripay
        return response()->json(['success' => true]);
    }
}
