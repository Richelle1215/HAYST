<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\CustomerOrderController;

use App\Http\Controllers\Seller\SellerProfileController;
use App\Http\Controllers\Seller\SellerOrderController;
/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/
Route::get('/', [StoreController::class, 'index'])->name('products.index');
Route::get('/store', [StoreController::class, 'index'])->name('store.index');
Route::get('/products/{product}', [StoreController::class, 'show'])->name('products.show');
Route::get('/category/{category}', [StoreController::class, 'filter'])->name('products.filter');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// Clear cart session (for testing)
Route::get('/clear-cart-session', function() {
    session()->forget('cart');
    return redirect()->route('cart.index')->with('success', 'Cart cleared!');
});

/*
|--------------------------------------------------------------------------
| Logout Route
|--------------------------------------------------------------------------
*/
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('products.index');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Routes (Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [CustomerProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [CustomerProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [CustomerProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::delete('/profile', [CustomerProfileController::class, 'delete'])->name('profile.delete');
    
    // Orders
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [CustomerOrderController::class, 'show'])->name('orders.show');
Route::post('/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    // Cart
 Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    
    // Checkout routes
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/process', [CheckoutController::class, 'process'])->name('process');
});
});
/*
|--------------------------------------------------------------------------
| Seller Routes (Authenticated & Verified)
|--------------------------------------------------------------------------
*/
// ✅ Single consolidated seller route group
Route::middleware(['auth', 'verified', 'seller'])->prefix('seller')->name('seller.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('seller.dashboard');
    })->name('dashboard');
    
    // Products
    Route::resource('products', ProductController::class);
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Orders
    Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [SellerOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/seller/orders/{id}', [SellerOrderController::class, 'show'])->name('seller.orders.show');

    // Profile
    Route::get('/profile', [SellerProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [SellerProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [SellerProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::delete('/profile', [SellerProfileController::class, 'delete'])->name('profile.delete');

    
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Authenticated & Verified)
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Fallback Dashboard Route
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return redirect('/customer/dashboard');
})->middleware('auth')->name('dashboard');
