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
        if ($id == '') {
            // Add Category
            $title = "Add Category";
            $category = new Category();
            $message = "Category added successfully.";
        } else {
            // Edit Category
            $title = "Edit Category";
        }

        if ($request->isMethod('post')) {
            $data = $request->all();

            // Upload Admin Image
            if ($request->hasFile('category_image')) {
                $image_tmp = $request->file('category_image');
                if ($image_tmp->isValid()) {
                    // Get Image Extension
                    $extension = $image_tmp->getClientOriginalExtension();
                    // create image manager with desired driver
                    $manager = new ImageManager(new Driver());
                    // open an image file
                    $image = $manager->read($image_tmp);
                    // Generate New Image Name
                    $imageName = rand(11111, 99999) . '.' . $extension;
                    $imagePath = 'front/img/categories/' . $imageName;
                    // Upload the category Image
                    $image->save($imagePath);
                    $category->category_image = $imageName;

                }
            } else {
                $category->category_image = "";
            }

            $category->category_name = $data['category_name'];
            $category->category_discount = $data['category_discount'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->meta_title = $data['meta_title'];
            $category->meta_description = $data['meta_description'];
            $category->meta_keywords = $data['meta_keywords'];
            $category->status = 1;
            $category->save();

            return redirect('admin/categories')->with('success', $message);
        }

        return view('admin.categories.add_edit_category', compact('title', 'id'));
    }

    public function deleteCategory($id)
    {
        Category::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Category deleted successfully.');
    }
}
