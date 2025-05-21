<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Balance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds to fix any balance inconsistencies.
     */
    public function run(): void
    {
        $this->command->info('Fixing balance inconsistencies...');
        
        // Get all users
        $users = User::all();
        
        foreach ($users as $user) {
            $this->command->info("Processing user: {$user->name}");
            
            // Get user's transactions
            $deposits = Transaction::where('user_id', $user->id)
                ->where('type', 'deposit')
                ->where('status', 'success')
                ->sum('amount');
                
            $expenses = Transaction::where('user_id', $user->id)
                ->whereIn('type', ['purchase', 'transfer'])
                ->where('status', 'success')
                ->sum('amount');
                
            $correctBalance = $deposits - $expenses;
            
            // Create or update user's balance
            $balance = Balance::updateOrCreate(
                ['user_id' => $user->id],
                ['balance' => $correctBalance > 0 ? $correctBalance : 0]
            );
            
            $this->command->info("User {$user->name}: Deposits={$deposits}, Expenses={$expenses}, Correct Balance={$correctBalance}, Updated Balance={$balance->balance}");
        }
        
        $this->command->info('Balance fix completed!');
    }
} 