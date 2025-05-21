<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('SetLocale Middleware: Handling request.');
        
        // Prioritas: 1. Session 2. Cookie 3. Default config
        $locale = null;
        
        // Cek dari Session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            Log::info('SetLocale Middleware: Found locale in session: ' . $locale);
        }
        // Jika tidak ada di session, cek di cookie
        else if ($request->hasCookie('locale')) {
            $locale = $request->cookie('locale');
            Log::info('SetLocale Middleware: Found locale in cookie: ' . $locale);
        }
        // Jika tidak ditemukan dimana pun, gunakan default
        else {
            $locale = config('app.locale');
            Log::info('SetLocale Middleware: Using default locale from config: ' . $locale);
        }
        
        // Pastikan locale valid
        if (in_array($locale, ['en', 'id'])) {
            App::setLocale($locale);
            Session::put('locale', $locale); // Sinkronkan session
            Log::info('SetLocale Middleware: App locale set to: ' . $locale);
        } else {
            $fallback = config('app.fallback_locale', 'en');
            App::setLocale($fallback);
            Session::put('locale', $fallback); // Sinkronkan session
            Log::warning('SetLocale Middleware: Invalid locale, using fallback: ' . $fallback);
        }

        return $next($request);
    }
}
