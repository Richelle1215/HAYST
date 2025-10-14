<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;



Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Public Store Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [StoreController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [StoreController::class, 'show'])->name('products.show');
Route::get('/category/{category}', [StoreController::class, 'filter'])->name('products.filter');


/*
|--------------------------------------------------------------------------
| SELLER (Admin) Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('seller')->group(function () {

    // You can use a dashboard view here if needed.
    // Route::get('/', function () { return view('dashboard'); })->name('dashboard');

    // CRUD for Categories
    Route::resource('categories', CategoryController::class);

    // CRUD for Products (with images, price, stock, description)
    Route::resource('products', ProductController::class);
});

// Public Store Routes
Route::get('/', [StoreController::class, 'index'])->name('products.index'); 
Route::get('/products/{product}', [StoreController::class, 'show'])->name('products.show');
Route::get('/category/{category}', [StoreController::class, 'filter'])->name('products.filter');