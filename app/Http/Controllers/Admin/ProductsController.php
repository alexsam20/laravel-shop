<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function products()
    {
        $products = Product::with('category')->get()->toArray();
//        dd($products);

        return view('admin.products.products', compact('products'));
    }

    public function updateProductStatus(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
        }

        Product::where('id', $data['product_id'])->update(['status' => $status]);

        return response()->json(['status' => $status, 'product_id' => $data['product_id']]);
    }

    public function addEditProduct(Request $request, $id = null)
    {
        if ($id == null) {
            // Add Product
            $title = "Add Product";
            $product = new Product();
            $productData = [];
            $message = "Product added successfully.";
        } else {
            // Edit Product
            $title = "Edit Product";
            $product = Product::with('category')->findOrFail($id);
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            dd($data);
            $request->validate([
                'category_id' => 'required|max:255',
                'product_name' => 'required|regex:/^[\pL\s\-]+$/u|max:200',
                'product_code' => 'required|regex:/^[\w-]*$/|max:20',
                'product_color' => 'required|regex:/^[\pL\s\-]+$/u|max:200',
                'family_color' => 'required|regex:/^[\pL\s\-]+$/u|max:200',
                'product_price' => 'required|numeric',
            ]);

            /*$messages = [
                'category_id.required' => 'Category is required.',
                'product_name.required' => 'Product Name is required.',
                'product_name.regex' => 'Valid Product Name is required.',
                'product_code.required' => 'Product Code is required.',
                'product_code.regex' => 'Valid Product Code is required.',
                'product_color.required' => 'Product Color is required.',
                'product_color.regex' => 'Valid Product Color is required.',
                'family_color.required' => 'Family Color is required.',
                'family_color.regex' => 'Valid Family Color is required.',
                'product_price.required' => 'Product Price is required.',
                'product_price.numeric' => 'Valid Product Price must be numeric.',
            ];*/

            // Update Product Video
            if ($request->hasFile('product_video')) {
                $video_tmp = $request->file('product_video');
                if ($video_tmp->isValid()) {
                    $videoNameFromSave = time() . '-' . $video_tmp->getClientOriginalName();
                    $video_tmp->move('front/video/', $videoNameFromSave);
                    // Save Video Name in Product Table
                    $product->product_video = $videoNameFromSave;
                }
            }

            // Save Product
            $product->category_id = $data['category_id'];
            $product->brand_id = $data['brand_id'] ?? 0;
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            $product->family_color = $data['family_color'];
            $product->group_code = $data['group_code'];
            $product->product_price = $data['product_price'];
            $product->product_weight = $data['product_weight'] ;
            $product->product_discount = $data['product_discount'] ?? 0;
            if (!empty($data['product_discount']) && $data['product_discount'] > 0) {
                $product->discount_type = 'product';
                $product->final_price = $data['final_price'] - ($data['final_price'] * $data['product_discount']) / 100;
            } else {
                $getCategoryDiscount = Category::select('category_discount')->where('id', $data['category_id'])->first();
                if ($getCategoryDiscount->category_discount == 0) {
                    $product->discount_type = '';
                    $product->final_price = $data['product_price'];
                }
            }

            $product->description = $data['description'];
            /*$product->search_keywords = $data['search_keywords'];*/
            $product->wash_care = $data['wash_care'];
            $product->fabric = $data['fabric'];
            $product->pattern = $data['pattern'];
            $product->sleeve = $data['sleeve'];
            $product->fit = $data['fit'];
            $product->occasion = $data['occasion'];
            $product->meta_title = $data['meta_title'];
            $product->meta_description = $data['meta_description'];
            $product->meta_keywords = $data['meta_keywords'];
            if (!empty($data['is_featured'])) {
                $product->is_featured = $data['is_featured'];
            }
            $product->save();

            return redirect('admin/products')->with('success_message', $message);
        }

        // Get Categories
        $getCategories = Category::getCategories();
        // Products Filters
        $productsFilters = Product::productsFilters();

        return view('admin.products.add_edit_product', compact('title', 'getCategories', 'productsFilters'));
    }

    public function deleteProduct($id)
    {
        Product::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Product deleted successfully.');
    }
}
