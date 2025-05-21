<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LocaleController extends Controller
{
    /**
     * Set the application locale.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLocale($locale)
    {
        Log::info('LocaleController: Attempting to set locale to: ' . $locale);
        
        // Pastikan locale valid sebelum disimpan
        if (in_array($locale, ['en', 'id'])) {
            // Reset session terlebih dahulu untuk menghindari konflik
            Session::forget('locale');
            
            // Setel locale baru
            Session::put('locale', $locale);
            App::setLocale($locale);
            
            Log::info('LocaleController: Session locale set to: ' . Session::get('locale'));
            Log::info('LocaleController: App locale set to: ' . App::getLocale());
        } else {
            Log::warning('LocaleController: Invalid locale requested: ' . $locale);
        }
        
        // Untuk memastikan tidak ada masalah cache, gunakan Redirect::to dengan URL lengkap
        // daripada Redirect::back() yang bisa menyimpan state lama
        $referer = request()->headers->get('referer');
        
        if ($referer) {
            return redirect($referer)->withCookie(cookie()->forever('locale', $locale));
        }
        
        return redirect('/')->withCookie(cookie()->forever('locale', $locale));
    }
}
