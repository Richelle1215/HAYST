        <?php

        use Illuminate\Support\Facades\Route;
        use App\Http\Controllers\CategoryController;
        use App\Http\Controllers\ProductController;
        use App\Http\Controllers\StoreController;
        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Auth;

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
        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ])->prefix('seller')->name('seller.')->group(function () {
            Route::get('/dashboard', function () {
                return view('seller.dashboard'); // resources/views/seller/dashboard.blade.php
            })->name('dashboard');

            Route::resource('categories', CategoryController::class);
            Route::resource('products', ProductController::class);
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
