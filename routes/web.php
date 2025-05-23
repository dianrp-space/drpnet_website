<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\GalleryViewController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\ShopClosedController;

// Route sederhana untuk halaman toko tutup
Route::get('shop/closed', function () {
    return view('shop.closed');
})->name('shop.closed');

// Route dasar
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', \App\Http\Middleware\RedirectIfProfileIncomplete::class])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Shop routes (public browsing)
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

// Authenticated routes for purchase and download
Route::middleware(['auth', \App\Http\Middleware\RedirectIfProfileIncomplete::class])->group(function () {
    Route::post('/shop/{product:slug}/purchase', [ShopController::class, 'purchase'])->name('shop.purchase');
    Route::get('/shop/{product:slug}/download', [ShopController::class, 'download'])->name('shop.download');
    Route::get('/my-purchases', [ShopController::class, 'myPurchases'])->name('shop.my-purchases');
    
    // Payment routes
    Route::get('/payment/{purchase}', [ShopController::class, 'payment'])->name('shop.payment');
    Route::post('/payment/{purchase}/process', [ShopController::class, 'processPayment'])->name('shop.process-payment');
    Route::post('/payment/{purchase}/cancel', [ShopController::class, 'cancelPayment'])->name('shop.cancel-payment');
    Route::get('/payment/{purchase}/success', [ShopController::class, 'paymentSuccess'])->name('shop.payment-success');
    
    // Cart routes
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/product/{product}', [\App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/item/{cartItem}/update', [\App\Http\Controllers\CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::delete('/cart/item/{cartItem}', [\App\Http\Controllers\CartController::class, 'removeItem'])->name('cart.remove');
    Route::post('/cart/clear', [\App\Http\Controllers\CartController::class, 'clearCart'])->name('cart.clear');
    Route::post('/cart/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
});

// Admin and authenticated user routes
Route::middleware(['auth', \App\Http\Middleware\RedirectIfProfileIncomplete::class])->group(function () {
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('tags', \App\Http\Controllers\TagController::class);
    Route::resource('posts', \App\Http\Controllers\PostController::class);
    Route::resource('galleries', GalleryController::class);
    Route::resource('products', ProductController::class);
    
    // Toggle shop status route
    Route::post('/products/toggle-shop-status', [ProductController::class, 'toggleShopStatus'])->name('products.toggle-shop-status');
});

// Admin routes
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    // Settings routes
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    
    // WhatsApp testing
    Route::get('/settings/whatsapp-test', [\App\Http\Controllers\Admin\SettingsController::class, 'whatsappTest'])->name('settings.whatsapp-test');
    Route::post('/settings/whatsapp-test', [\App\Http\Controllers\Admin\SettingsController::class, 'sendWhatsappTest'])->name('settings.whatsapp-test.send');
    
    // Slides management
    Route::get('/settings/slides', [\App\Http\Controllers\Admin\SettingsController::class, 'slides'])->name('settings.slides');
    Route::post('/settings/slides', [\App\Http\Controllers\Admin\SettingsController::class, 'storeSlide'])->name('settings.slides.store');
    Route::put('/settings/slides/{slide}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateSlide'])->name('settings.slides.update');
    Route::delete('/settings/slides/{slide}', [\App\Http\Controllers\Admin\SettingsController::class, 'deleteSlide'])->name('settings.slides.delete');
});

// Public blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Blog Comments
Route::get('/blog/{post}/comments', [\App\Http\Controllers\BlogCommentController::class, 'getComments'])->name('blog.comments.get');
Route::post('/blog/{post}/comments', [\App\Http\Controllers\BlogCommentController::class, 'store'])->name('blog.comments.store');
Route::delete('/blog/comments/{comment}', [\App\Http\Controllers\BlogCommentController::class, 'destroy'])->name('blog.comments.destroy');

// Public gallery routes
Route::get('/gallery', [GalleryViewController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{gallery:slug}', [GalleryViewController::class, 'show'])->name('gallery.show');

// Gallery Comments
Route::get('/gallery/{gallery}/comments', [\App\Http\Controllers\GalleryCommentController::class, 'getComments'])->name('gallery.comments.get');
Route::post('/gallery/{gallery}/comments', [\App\Http\Controllers\GalleryCommentController::class, 'store'])->name('gallery.comments.store');
Route::delete('/gallery/comments/{comment}', [\App\Http\Controllers\GalleryCommentController::class, 'destroy'])->name('gallery.comments.destroy');

// Balance Management Routes
Route::middleware(['auth', \App\Http\Middleware\RedirectIfProfileIncomplete::class])->group(function () {
    // Balance Dashboard
    Route::get('/balance', [App\Http\Controllers\BalanceController::class, 'index'])->name('balance.index');
    Route::get('/balance/transaction/{id}', [App\Http\Controllers\BalanceController::class, 'transaction'])->name('balance.transaction');
    Route::get('/balance/history', [App\Http\Controllers\BalanceController::class, 'history'])->name('balance.history');
    
    // Deposit Routes
    Route::get('/deposit', [App\Http\Controllers\DepositController::class, 'showForm'])->name('deposit.form');
    Route::post('/deposit', [App\Http\Controllers\DepositController::class, 'process'])->name('deposit.process');
    Route::get('/deposit/payment/{transaction}', [App\Http\Controllers\DepositController::class, 'showPayment'])->name('deposit.payment');
    Route::post('/deposit/payment/{transaction}/complete', [App\Http\Controllers\DepositController::class, 'complete'])->name('deposit.complete');
    Route::post('/deposit/payment/{transaction}/cancel', [App\Http\Controllers\DepositController::class, 'cancel'])->name('deposit.cancel');
    
    // Transfer Routes
    Route::get('/transfer', [App\Http\Controllers\TransferController::class, 'showForm'])->name('transfer.form');
    Route::post('/transfer', [App\Http\Controllers\TransferController::class, 'process'])->name('transfer.process');
    Route::get('/transfer/history', [App\Http\Controllers\TransferController::class, 'history'])->name('transfer.history');
    Route::get('/transfer/{id}', [App\Http\Controllers\TransferController::class, 'details'])->name('transfer.details');
});

// Tripay Callback Route (not requiring auth)
Route::post('/payment/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');

// Route untuk mengganti bahasa
Route::get('locale/{locale}', [LocaleController::class, 'setLocale'])->name('locale.set');

// Google Socialite Routes
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

// Complete Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/complete-profile', [App\Http\Controllers\CompleteProfileController::class, 'show'])
        ->name('complete-profile');
    Route::post('/complete-profile', [App\Http\Controllers\CompleteProfileController::class, 'update'])
        ->name('complete-profile.update');
});

// Grup route untuk toko yang akan dicek statusnya
Route::middleware([\App\Http\Middleware\CheckShopStatus::class])->group(function () {
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/product/{product:slug}', [ShopController::class, 'show'])->name('shop.show');
    
    // Route yang memerlukan login untuk diakses
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::post('/shop/product/{product}/purchase', [ShopController::class, 'purchase'])->name('shop.purchase');
        Route::get('/shop/payment/{purchase}', [ShopController::class, 'payment'])->name('shop.payment');
        Route::post('/shop/payment/{purchase}/process', [ShopController::class, 'processPayment'])->name('shop.process-payment');
        Route::get('/shop/payment-success/{purchase}', [ShopController::class, 'paymentSuccess'])->name('shop.payment-success');
        Route::post('/shop/payment/{purchase}/cancel', [ShopController::class, 'cancelPayment'])->name('shop.cancel-payment');
        Route::get('/shop/download/{product}', [ShopController::class, 'download'])->name('shop.download');
        Route::get('/my-purchases', [ShopController::class, 'myPurchases'])->name('shop.my-purchases');

        // Cart Routes
        Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{product}', [App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
        Route::patch('/cart/update/{cartItem}', [App\Http\Controllers\CartController::class, 'updateQuantity'])->name('cart.update');
        Route::delete('/cart/remove/{cartItem}', [App\Http\Controllers\CartController::class, 'removeItem'])->name('cart.remove');
        Route::post('/cart/clear', [App\Http\Controllers\CartController::class, 'clearCart'])->name('cart.clear');
        Route::post('/cart/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
    });
});

require __DIR__.'/auth.php';
