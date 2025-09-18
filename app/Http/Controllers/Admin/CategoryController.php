<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories()
    {
        $categories = Category::with('parentCategory')->get();
//        dd($categories);

        return view('admin.categories.categories', compact('categories'));
    }
}
