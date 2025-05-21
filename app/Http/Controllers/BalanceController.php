<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Apply auth middleware to all methods in this controller
        $this->middleware(['auth']);
    }
    
    /**
     * Display the user's balance and transaction history.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ensure we get the fresh data from database, not from cache
        $user = User::find($user->id); // Get fresh instance from database
        
        // Ensure user has a balance record - use fresh data
        $balance = $user->balance()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);
        
        // Refresh the balance from the database
        $balance->refresh();
        
        // Get latest transactions
        $transactions = $user->transactions()
                            ->latest()
                            ->paginate(15);
        
        return view('balance.index', compact('balance', 'transactions'));
    }

    /**
     * Show deposit form.
     */
    public function deposit()
    {
        $user = Auth::user();
        $balance = $user->balance()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);
        
        return view('balance.deposit', compact('balance'));
    }

    /**
     * Show transfer form.
     */
    public function transfer()
    {
        $user = Auth::user();
        $balance = $user->balance()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);
        
        return view('balance.transfer', compact('balance'));
    }

    /**
     * Show transaction details.
     */
    public function transaction($id)
    {
        $user = Auth::user();
        $transaction = Transaction::findOrFail($id);
        
        // Check if transaction belongs to user
        if ($transaction->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('balance.transaction', compact('transaction'));
    }
    
    /**
     * Show the transaction history.
     */
    public function history()
    {
        $user = Auth::user();
        
        // Get all transactions with pagination
        $transactions = $user->transactions()
                             ->with(['user'])
                             ->latest()
                             ->paginate(20);
        
        return view('balance.history', compact('transactions'));
    }
}
