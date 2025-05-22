<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopClosedController extends Controller
{
    /**
     * Menampilkan halaman toko tutup.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shop.closed');
    }
} 