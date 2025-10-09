<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminsRole;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductsAttributes;
use App\Models\ProductsImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProductsController extends Controller
{
    public function products()
    {
        Session::put('page', 'products');
        $products = Product::with('category')->get()->toArray();

        // Set Admin/Sab Admins Permissions for Products
        $productsModuleCount = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'products'])->count();
        $productsModule = [];
        if (Auth::guard('admin')->user()->type == 'admin') {
            $productsModule['view_access'] = 1;
            $productsModule['edit_access'] = 1;
            $productsModule['full_access'] = 1;
        } else if ($productsModuleCount == 0) {
            $message = "This feature is restricted for you";
            return redirect('admin/dashboard')->with('error_message', $message);
        } else {
            $productsModule = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'products'])
                ->first()
                ->toArray();
        }

        return view('admin.products.products', compact('products', 'productsModule'));
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
        Session::put('page', 'products');
        if ($id == null) {
            // Add Product
            $title = "Add Product";
            $product = new Product();
            $message = "Product added successfully.";
        } else {
            // Edit Product
            $title = "Edit Product";
            $product = Product::with(['images', 'attributes'])->find($id);
            /*dd($product)*/;
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

            // Sort Product Images
            if ($id !== null) {
                if (isset($data['image'])) {
                    foreach ($data['image'] as $key => $image) {
                        ProductsImage::where(['product_id' => $product_id, 'image' => $image])
                            ->update(['image_sort' => $data['image_sort'][$key]]);
                    }
                }
            }

            // Add Product Attributes
            foreach ($data['sku'] as $key => $value) {
                if (!empty($value)) {
                    // SKU already exists check
                    $countSKU = ProductsAttributes::where('sku', $value)->count();
                    if ($countSKU > 0) {
                        return redirect()->back()->with('error', 'SKU already exists. Please try another SKU.');
                    }
                    // Size already exists check
                    $countSize = ProductsAttributes::where(['product_id' => $product_id, 'size' => $data['size'][$key]])->count();
                    if ($countSize > 0) {
                        return redirect()->back()->with('error', 'Size already exists. Please try another Size.');
                    }

                    $attribute = new ProductsAttributes();
                    $attribute->product_id = $product_id;
                    $attribute->sku = $value;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                }
            }

            // Edit Product Attributes
            if (isset($data['attributeId'])) {
                foreach ($data['attributeId'] as $attributes => $attribute) {
                    if (!empty($attribute)) {
                        ProductsAttributes::where(['id' => $data['attributeId'][$attributes]])
                            ->update(['price' => $data['price'][$attributes], 'stock' => $data['stock'][$attributes]]);
                    }
                }
            }

            return redirect('admin/products')->with('success_message', $message);
        }

        // Get Categories
        $getCategories = Category::getCategories();
        // Get Brands
        $getBrands  = Brand::where('status', 1)->get()->toArray();
        // Products Filters
        $productsFilters = Product::productsFilters();

        return view('admin.products.add_edit_product', compact('title', 'getCategories', 'productsFilters', 'product', 'getBrands'));
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

    public function deleteProductImage($id)
    {
        $productImage = ProductsImage::select('image')->where('id', $id)->first();
        $smallImageFile = 'front/images/products/small/' . $productImage->image;
        $mediumImageFile = 'front/images/products/medium/' . $productImage->image;
        $largeImageFile = 'front/images/products/large/' . $productImage->image;
        if (file_exists($smallImageFile)) {
            unlink($smallImageFile);
        }
        if (file_exists($mediumImageFile)) {
            unlink($mediumImageFile);
        }
        if (file_exists($largeImageFile)) {
            unlink($largeImageFile);
        }

        ProductsImage::where('id', $id)->delete();

        return redirect()->back()->with('success_message', 'Product Image has been deleted successfully.');
    }

    public function updateAttributeStatus(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
        }

        ProductsAttributes::where('id', $data['attribute_id'])->update(['status' => $status]);

        return response()->json(['status' => $status, 'attribute_id' => $data['attribute_id']]);
    }

    public function deleteAttribute($id)
    {
        ProductsAttributes::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Attribute deleted successfully.');
    }
}
