<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function categories()
    {
        Session::put('page', 'categories');
        $categories = Category::with('parentCategory')->get();
//        dd($categories);

        return view('admin.categories.categories', compact('categories'));
    }

    public function updateCategoryStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
        }

        Category::where('id', $data['category_id'])->update(['status' => $status]);

        return response()->json(['status' => $status, 'category_id' => $data['category_id']]);
    }

    public function addEditCategory(Request $request, $id = null)
    {
        if ($id == '') {
            // Add Category
            $title = "Add Category";
        } else {
            // Edit Category
            $title = "Edit Category";
        }

        return view('admin.categories.add_edit_category', compact('title', 'id'));
    }

    public function deleteCategory($id)
    {
        Category::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Category deleted successfully.');
    }
}
