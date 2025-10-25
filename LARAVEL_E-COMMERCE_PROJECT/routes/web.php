        <?php

        use Illuminate\Support\Facades\Route;
        use App\Http\Controllers\CategoryController;
        use App\Http\Controllers\ProductController;
        use App\Http\Controllers\StoreController;
        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Auth;
        use App\Http\Controllers\Seller\OrderController;
        use App\Http\Controllers\Seller\SellerProfileController;
      
        /*
        |--------------------------------------------------------------------------
        | Public Routes
        |--------------------------------------------------------------------------
        */
        Route::get('/', [StoreController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [StoreController::class, 'show'])->name('products.show');
        Route::get('/category/{category}', [StoreController::class, 'filter'])->name('products.filter');

        /*
        |--------------------------------------------------------------------------
        | Customer Dashboard (Default after login)
        |--------------------------------------------------------------------------
        */
Route::get('/home', function () {
    return redirect()->route('products.index');
});


        /*
        |--------------------------------------------------------------------------
        | Seller Routes
        |--------------------------------------------------------------------------
        */
    Route::middleware(['auth', 'verified'])->prefix('seller')->name('seller.')->group(function () {

        Route::get('/dashboard', function () {
            return view('seller.dashboard');
        })->name('dashboard');

        // ✅ Product Routes
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::get('/profile', [SellerProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [SellerProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [SellerProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::get('/orders', [OrderController::class, 'index'])->name('seller.orders.index');
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('store', [StoreController::class, 'index'])->name('store.index');
    // PROFILE ROUTES
        Route::get('/profile', [SellerProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/update', [SellerProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/update-password', [SellerProfileController::class, 'updatePassword'])->name('profile.updatePassword');
        
        // ✅ NEW: ACCOUNT DELETION ROUTE
        Route::delete('/profile', [SellerProfileController::class, 'delete'])->name('profile.delete');
    });


        /*
        |--------------------------------------------------------------------------
        | Admin Routes
        |--------------------------------------------------------------------------
        */
        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ])->prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', function () {
                return view('admin.dashboard'); // resources/views/admin/dashboard.blade.php
            })->name('dashboard');
        });

        /*
        |--------------------------------------------------------------------------
        | Fallback for old dashboard link (optional)
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', function () {
            return redirect('/home');
        })->name('dashboard');

//preventeng back history
        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
            \App\Http\Middleware\PreventBackHistory::class,
        ])->group(function () {
            // your protected routes here
        });
        Route::post('/logout', function (Request $request) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // ✅ Redirect to your store home
            return redirect()->route('products.index');
        })->name('logout');

        //store routes
        Route::get('/', [StoreController::class, 'index'])->name('products.index');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('seller.products.edit');
   Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('seller.products.destroy');

 
use App\Http\Controllers\CartController;

// Cart Routes
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');


Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

Route::get('/clear-cart-session', function() {
    session()->forget('cart');
    return redirect()->route('cart.index')->with('success', 'Cart cleared!');
});

Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');

Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');


//customer
