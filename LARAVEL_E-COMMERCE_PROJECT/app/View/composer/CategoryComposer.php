<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Category;

class CategoryComposer
{
    public function compose(View $view)
    {
        $categories = Category::all();
        $view->with('categories', $categories);
    }
}