<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing users with username based on their name
        $users = User::whereNull('username')->get();
        
        foreach ($users as $user) {
            // Generate username from name (lowercase, remove spaces)
            $baseUsername = Str::slug(Str::before($user->email, '@'));
            
            // If email-based username is empty, use name
            if (empty($baseUsername)) {
                $baseUsername = Str::slug($user->name);
            }
            
            // Make sure username is unique
            $username = $baseUsername;
            $counter = 1;
            
            while (User::where('username', $username)->where('id', '!=', $user->id)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }
            
            $user->username = $username;
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot be undone easily
    }
}; 