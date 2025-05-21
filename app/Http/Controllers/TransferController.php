<?php

namespace App\Http\Controllers;

use App\Models\BalanceTransfer;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
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
     * Show the transfer form.
     */
    public function showForm()
    {
        $user = Auth::user();
        $balance = $user->balance()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);
        
        return view('balance.transfer', compact('balance'));
    }
    
    /**
     * Process the transfer request.
     */
    public function process(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'recipient_email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:1000', // Minimum transfer 1k
            'description' => 'nullable|string|max:255',
        ]);
        
        $sender = Auth::user();
        $recipient = User::where('email', $validatedData['recipient_email'])->first();
        
        // Check if user is trying to transfer to themselves
        if ($sender->id === $recipient->id) {
            return back()->withErrors([
                'recipient_email' => 'You cannot transfer to yourself.',
            ])->withInput();
        }
        
        // Check if user has enough balance
        if ($sender->balance < $validatedData['amount']) {
            return back()->withErrors([
                'amount' => 'Insufficient balance. Your current balance is ' . number_format($sender->balance, 2),
            ])->withInput();
        }
        
        try {
            // Use transaction to ensure data consistency
            DB::beginTransaction();
            
            // Create the transfer record
            $transfer = BalanceTransfer::create([
                'from_user_id' => $sender->id,
                'to_user_id' => $recipient->id,
                'amount' => $validatedData['amount'],
                'status' => 'success',
                'description' => $validatedData['description'] ?? "Transfer to {$recipient->name}",
            ]);
            
            // Update sender's balance
            $senderBalance = $sender->balance()->firstOrCreate(['user_id' => $sender->id], ['balance' => 0]);
            $senderBalance->decrement('balance', $validatedData['amount']);
            
            // Create transaction record for sender
            $senderTransaction = $sender->transactions()->create([
                'type' => 'transfer',
                'amount' => $validatedData['amount'],
                'status' => 'success',
                'description' => $validatedData['description'] ?? "Transfer to {$recipient->name}",
                'related_id' => $transfer->id,
            ]);
            
            // Update recipient's balance
            $recipientBalance = $recipient->balance()->firstOrCreate(['user_id' => $recipient->id], ['balance' => 0]);
            $recipientBalance->increment('balance', $validatedData['amount']);
            
            // Create transaction record for recipient
            $recipientTransaction = $recipient->transactions()->create([
                'type' => 'transfer',
                'amount' => $validatedData['amount'],
                'status' => 'success',
                'description' => "Transfer from {$sender->name}",
                'related_id' => $transfer->id,
            ]);
            
            DB::commit();
            
            // Send WhatsApp notifications
            $whatsAppService = app(WhatsAppService::class);
            $whatsAppService->sendTransferNotifications(
                $sender, 
                $recipient, 
                $validatedData['amount'],
                $senderBalance->balance,
                $recipientBalance->balance
            );
            
            return redirect()->route('balance.index')
                    ->with('success', 'Successfully transferred ' . number_format($validatedData['amount'], 2) . ' to ' . $recipient->name);
                    
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            \Log::error('Transfer failed: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Transfer failed. Please try again later.',
            ])->withInput();
        }
    }
    
    /**
     * Show history of transfers.
     */
    public function history()
    {
        $user = Auth::user();
        
        // Get sent transfers
        $sentTransfers = $user->sentTransfers()
                             ->with(['toUser'])
                             ->latest()
                             ->paginate(10, ['*'], 'sent_page');
        
        // Get received transfers
        $receivedTransfers = $user->receivedTransfers()
                                 ->with(['fromUser'])
                                 ->latest()
                                 ->paginate(10, ['*'], 'received_page');
        
        return view('balance.transfer-history', compact('sentTransfers', 'receivedTransfers'));
    }
    
    /**
     * Show details of a specific transfer.
     */
    public function details($id)
    {
        $user = Auth::user();
        $transfer = BalanceTransfer::findOrFail($id);
        
        // Check if user is involved in this transfer
        if ($transfer->from_user_id !== $user->id && $transfer->to_user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('balance.transfer-details', compact('transfer'));
    }
}
