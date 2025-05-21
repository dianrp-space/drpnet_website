<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryViewController extends Controller
{
    /**
     * Display a listing of public galleries
     */
    public function index()
    {
        $galleries = Gallery::with('user')
            ->latest()
            ->paginate(12);
            
        return view('gallery.index', compact('galleries'));
    }
    
    /**
     * Display the specified gallery
     */
    public function show(Gallery $gallery)
    {
        return view('gallery.show', compact('gallery'));
    }
} 