<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting; // Pastikan model Setting di-import

class CheckShopStatus
{
    /**
     * Middleware untuk memeriksa status toko digital
     * 
     * Middleware ini akan memeriksa apakah toko dalam keadaan buka atau tutup.
     * Jika toko ditutup, pengguna biasa akan diarahkan ke halaman 'shop.closed',
     * sedangkan admin tetap dapat mengakses semua halaman toko.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Ambil status toko dari database, default 'open' jika tidak ada setting
        $shopStatus = Setting::get('shop_status', 'open');

        // Debug - hapus setelah masalah teratasi
        // \Log::info('Shop status check', ['status' => $shopStatus, 'path' => $request->path(), 'is_shop_closed' => $request->routeIs('shop.closed')]);
        
        // Jika toko ditutup dan pengguna bukan admin
        if ($shopStatus === 'closed' && (!Auth::check() || Auth::user()->role !== 'admin')) {
            // Cek apakah request saat ini bukan ke halaman toko tutup untuk menghindari redirect loop
            if ($request->path() !== 'shop/closed') {
                return redirect('shop/closed');
            }
        }

        return $next($request);
    }
}
