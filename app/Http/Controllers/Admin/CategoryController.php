<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
        $getCategories = Category::getCategories();
        if ($id == '') {
            // Add Category
            $title = "Add Category";
            $category = new Category();
            $message = "Category added successfully.";
        } else {
            // Edit Category
            $title = "Edit Category";
            $category = Category::find($id);
            $message = "Category updated successfully.";
        }

        if ($request->isMethod('post')) {
            $data = $request->all();

            // CMS Pages Validations
            $request->validate([
                'category_name' => 'required|max:255',
                'url' => 'required|max:255|unique:categories,url',
            ]);

            // Upload Admin Image
            $category->category_image = $this->updateImage($request, 'category_image', 'front/img/categories/');

            $category->category_name = $data['category_name'];
            $category->parent_id = $data['parent_id'];
            $category->category_discount = $data['category_discount'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->meta_title = $data['meta_title'];
            $category->meta_description = $data['meta_description'];
            $category->meta_keywords = $data['meta_keywords'];
            $category->status = 1;
            $category->save();

            return redirect('admin/categories')->with('success_message', $message);
        }

        return view('admin.categories.add_edit_category', compact('title', 'getCategories'));
    }

    public function deleteCategory($id)
    {
        Category::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Category deleted successfully.');
    }
}
