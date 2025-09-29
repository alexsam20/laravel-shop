<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductsImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
            $message = "Product added successfully.";
        } else {
            // Edit Product
            $title = "Edit Product";
            $product = Product::find($id);
            $message = "Product updated successfully.";
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            $request->validate([
                'category_id' => 'required|max:255',
                'product_name' => 'required|regex:/^[\pL\s\-]+$/u|max:200',
                'product_code' => 'required|regex:/^[\w-]*$/|max:20',
                'product_color' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
                'family_color' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
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
                    $video_tmp->move('front/video/products/', $videoNameFromSave);
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
                $product->final_price = $data['product_price'] - ($data['product_price'] * $data['product_discount']) / 100;
            } else {
                $getCategoryDiscount = Category::select('category_discount')->where('id', $data['category_id'])->first();
                if ($getCategoryDiscount->category_discount == 0) {
                    $product->discount_type = '';
                    $product->final_price = $data['product_price'];
                } else {
                    $product->discount_type = 'category';
                    $product->final_price = $data['product_price'] - ($data['product_price'] * $getCategoryDiscount->category_discount) / 100;
                }
            }

            $product->description = $data['description'];
            $product->search_keywords = $data['search_keywords'];
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

            if ($id === null) {
                $product_id = DB::getPdo()->lastInsertId();
            } else {
                $product_id = $id;
            }
            // Upload Product Images
            if ($request->hasFile('product_images')) {
                $images = $request->file('product_images');
                $i = 1;
                foreach ($images as $key => $image) {
                    // create image manager with desired driver
                    $manager = new ImageManager(new Driver());
                    // open an image file
                    $img = $manager->read($image);
                    // Get Image Extension
                    $extension = $image->getClientOriginalExtension();
                    // Generate New Image Name
                    $imageName = 'product-' . rand(1111, 9999999) . '.' . $extension;
                    // Create Path for Small, Medium Large Pictures
                    $largeImagePath = 'front/images/products/large/' . $imageName;
                    $mediumImagePath = 'front/images/products/medium/' . $imageName;
                    $smallImagePath = 'front/images/products/small/' . $imageName;
                    $size = $img->size();
                    if ($size->isPortrait()) {
                        $large_width = 1040; $large_height = 1200;
                        $medium_width = 520; $medium_height = 600;
                        $small_width = 260; $small_height = 300;
                    } else {
                        $large_width = 1200; $large_height = 1040;
                        $medium_width = 520; $medium_height = 520;
                        $small_width = 300; $small_height = 260;
                    }

                    $img->resize($large_width, $large_height)->save($largeImagePath);
                    $img->resize($medium_width, $medium_height)->save($mediumImagePath);
                    $img->resize($small_width, $small_height)->save($smallImagePath);

                    // Insert Image Name in products_images table
                    $productImage = new ProductsImage();
                    $productImage->image = $imageName;
                    $productImage->product_id = $product_id;
                    $productImage->image_sort = $i;
                    $productImage->save();
                    $i++;
                }
            }

            return redirect('admin/products')->with('success_message', $message);
        }

        // Get Categories
        $getCategories = Category::getCategories();
        // Products Filters
        $productsFilters = Product::productsFilters();

        return view('admin.products.add_edit_product', compact('title', 'getCategories', 'productsFilters', 'product'));
    }

    public function deleteProduct($id)
    {
        Product::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Product deleted successfully.');
    }

    public function deleteProductVideo($id)
    {
        $productVideo = Product::select('product_video')->where('id', $id)->first();
        $file = 'front/video/products/' . $productVideo->product_video;

        if (file_exists($file)) {
            unlink($file);
        }

        Product::where('id', $id)->update(['product_video' => null]);

        return redirect()->back()->with('success_message', 'Product Video has been deleted successfully.');
    }
}
