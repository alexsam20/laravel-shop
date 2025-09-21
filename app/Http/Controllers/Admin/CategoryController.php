<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminsRole;
use App\Models\Category;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function categories()
    {
        Session::put('page', 'categories');
        $categories = Category::with('parentCategory')->get();

        // Set Admin/Sab Admins Permissions for Categories
        $categoriesModuleCount = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'categories'])->count();
        $categoriesModule = [];
        if (Auth::guard('admin')->user()->type == 'admin') {
            $categoriesModule['view_access'] = 1;
            $categoriesModule['edit_access'] = 1;
            $categoriesModule['full_access'] = 1;
        } else if ($categoriesModuleCount == 0) {
            $message = "This feature is restricted for you";
            return redirect('admin/dashboard')->with('error_message', $message);
        } else {
            $categoriesModule = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'categories'])
                ->first()
                ->toArray();
        }

        return view('admin.categories.categories', compact('categories', 'categoriesModule'));
    }

    public function updateCategoryStatus(Request $request): JsonResponse
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

    public function addEditCategory(Request $request, $id = null): View|Factory|Redirector|RedirectResponse
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
            if ($id == '') {
                $request->validate([
                    'category_name' => 'required|max:255',
                    'url' => 'required|max:255|unique:categories',
                ]);
            } else {
                $request->validate([
                    'category_name' => 'required|max:255',
                    'url' => 'required|max:255',
                ]);
            }

            // Upload Admin Image
            $category->category_image = $this->updateImage($request, 'category_image', 'front/img/categories/', $data);

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

        return view('admin.categories.add_edit_category', compact('title', 'getCategories', 'category'));
    }

    public function deleteCategory($id)
    {
        Category::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Category deleted successfully.');
    }

    public function deleteCategoryImage($id): RedirectResponse
    {
        // Get Category Image
        $categoryImage =  Category::select('category_image')->where('id', $id)->first();
        // Get Category Image Path
        $categoryImagePath = 'front/img/categories/' . $categoryImage->category_image;
        // Remove Category Images from folder if exist
        if (file_exists($categoryImagePath)) {
            unlink($categoryImagePath);
        }
        // Remove Category Images from categories table
        Category::where('id', $id)->update(['category_image' => null]);

        return redirect()->back()->with('success_message', 'Category deleted successfully.');
    }
}
