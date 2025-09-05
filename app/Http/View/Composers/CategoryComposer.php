<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Category;

class CategoryComposer
{
    public function compose(View $view)
    {
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $view->with('categories', $categories);
    }
}