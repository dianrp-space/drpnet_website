<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfProfileIncomplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Jika username atau phone kosong, redirect ke halaman complete profile
        if ((empty($user->username) || empty($user->phone)) && $request->route()->getName() !== 'complete-profile' && $request->route()->getName() !== 'complete-profile.update' && $request->route()->getName() !== 'logout') {
            return redirect()->route('complete-profile');
        }
        
        return $next($request);
    }
} 