<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share categories with header and other views that need it
        View::composer(['partials.header', 'products.*'], function ($view) {
            $categories = Category::all();
            $view->with('categories', $categories);
        });
        
        // Share cart count globally
        View::composer('*', function ($view) {
            $cartCount = count(session()->get('cart', []));
            $view->with('cartCount', $cartCount);
        });
    }
}