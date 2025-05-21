<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\WhatsAppService;

class CompleteProfileController extends Controller
{
    /**
     * Menampilkan form untuk melengkapi profil
     */
    public function show()
    {
        $user = Auth::user();
        
        // Hanya tampilkan form jika username atau nomor HP belum diisi
        if (!empty($user->username) && !empty($user->phone)) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.complete-profile', compact('user'));
    }
    
    /**
     * Memproses pengisian data profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validasi input
        $rules = [
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
        ];
        
        // Tambahkan validasi username jika kosong
        if (empty($user->username)) {
            $rules['username'] = ['required', 'string', 'max:255', 'unique:users,username,' . $user->id, 'alpha_dash'];
        }
        
        $request->validate($rules);
        
        // Update data user
        $user->phone = $request->phone;
        
        if (empty($user->username) && $request->has('username')) {
            $user->username = $request->username;
        }
        
        $user->save();
        
        // Kirim notifikasi WhatsApp selamat datang
        app(WhatsAppService::class)->sendWelcomeNotification($user);
        
        // Redirect ke dashboard dengan pesan sukses
        return redirect()->route('dashboard')
            ->with('status', 'Profil berhasil dilengkapi!');
    }
} 