<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        
        $products = Product::latest()->paginate(10);
        $shopStatus = Setting::get('shop_status', 'open');
        return view('products.index', compact('products', 'shopStatus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'file' => 'required|file|mimes:zip|max:10240', // max 10MB
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $filePath = $request->file('file')->store('products', 'public');
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('product_images', 'public') : null;

        Product::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => $request->boolean('is_active'),
            'file_path' => $filePath,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk digital berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'file' => 'nullable|file|mimes:zip|max:10240',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($product->file_path);
            $data['file_path'] = $request->file('file')->store('products', 'public');
        }
        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('product_images', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk digital berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        
        Storage::disk('public')->delete($product->file_path);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk digital berhasil dihapus.');
    }

    /**
     * Toggle shop status (open/closed)
     */
    public function toggleShopStatus(Request $request)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'shop_status' => 'required|in:open,closed',
        ]);

        Setting::set('shop_status', $request->shop_status, 'general');

        return redirect()->route('products.index')->with('success', 
            $request->shop_status === 'open' 
                ? 'Toko digital berhasil dibuka.' 
                : 'Toko digital berhasil ditutup.'
        );
    }
}
