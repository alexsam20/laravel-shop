<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminsRole;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BrandController extends Controller
{
    public function brands()
    {
        Session::put('page', 'brands');
        $brands = Brand::all();

        // Set Admin/Sab Admins Permissions for Brands
        $brandsModuleCount = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'brands'])->count();
        $brandsModule = [];
        if (Auth::guard('admin')->user()->type == 'admin') {
            $brandsModule['view_access'] = 1;
            $brandsModule['edit_access'] = 1;
            $brandsModule['full_access'] = 1;
        } else if ($brandsModuleCount == 0) {
            $message = "This feature is restricted for you";
            return redirect('admin/dashboard')->with('error_message', $message);
        } else {
            $brandsModule = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'brands'])
                ->first()
                ->toArray();
        }

        return view('admin.brands.brands', compact('brands', 'brandsModule'));
    }

    public function addEditBrand(Request $request, $id = null): View|Factory|Redirector|RedirectResponse
    {
        $getBrands = Brand::all();
        if ($id == '') {
            // Add Brand
            $title = "Add Brand";
            $brand = new Brand();
            $message = "Brand added successfully.";
        } else {
            // Edit Brand
            $title = "Edit Brand";
            $brand = Brand::find($id);
            $message = "Brand updated successfully.";
        }

        if ($request->isMethod('post')) {
            $data = $request->all();

            // Brand Validations
            if ($id == '') {
                $request->validate([
                    'brand_name' => 'required|max:255',
                    'url' => 'required|max:255|unique:brands',
                ]);
            } else {
                $request->validate([
                    'brand_name' => 'required|max:255',
                    'url' => 'required|max:255',
                ]);
            }

            // Upload Brand Image
            $brand->brand_image = $this->updateImage($request, 'brand_image', 'front/images/brands/', $data);
            // Upload Logo Image
            $brand->brand_logo = $this->updateImage($request, 'brand_logo', 'front/images/logos/', $data);

            // Remove Brand Discount from all products belong to specific Brand
            if (empty($data['brand_discount'])) {
                $data['brand_discount'] = 0;
                if ($id != '') {
                    $brandProducts = Product::where('brand_id', $id)->get()->toArray();
                    foreach ($brandProducts as $key => $product) {
                        if ($product['discount_type'] == 'brand') {
                            Product::where('id', $product['id'])
                                ->update(['discount_type' => '', 'final_price' => $product['product_price']]);
                        }
                    }
                }
            }

            $brand->brand_name = $data['brand_name'];
            $brand->brand_discount = $data['brand_discount'];
            $brand->description = $data['description'];
            $brand->url = $data['url'];
            $brand->meta_title = $data['meta_title'];
            $brand->meta_description = $data['meta_description'];
            $brand->meta_keywords = $data['meta_keywords'];
            $brand->status = 1;
            $brand->save();

            return redirect('admin/brands')->with('success_message', $message);
        }

        return view('admin.brands.add_edit_brand', compact('title', 'getBrands', 'brand'));
    }

    public function updateBrandStatus(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
        }

        Brand::where('id', $data['brand_id'])->update(['status' => $status]);

        return response()->json(['status' => $status, 'brand_id' => $data['brand_id']]);
    }

    public function deleteBrand($id)
    {
        Brand::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Brand deleted successfully.');
    }

    public function deleteBrandImage($id): RedirectResponse
    {
        // Get Brand Image
        $brandImage =  Brand::select('brand_image')->where('id', $id)->first();
        // Get Brand Image Path
        $brandImagePath = 'front/images/brands/' . $brandImage->brand_image;
        // Remove Brand Images from folder if exist
        if (file_exists($brandImagePath)) {
            unlink($brandImagePath);
        }
        // Remove Brand Images from brands table
        Brand::where('id', $id)->update(['brand_image' => null]);

        return redirect()->back()->with('success_message', 'Brand deleted successfully.');
    }

    public function deleteLogoImage($id): RedirectResponse
    {
        // Get Logo Image
        $logoImage =  Brand::select('brand_logo')->where('id', $id)->first();
        // Get Logo Image Path
        $logoImagePath = 'front/images/logos/' . $logoImage->brand_logo;
        // Remove Logo Images from folder if exist
        if (file_exists($logoImagePath)) {
            unlink($logoImagePath);
        }
        // Remove Logo Images from brands table
        Brand::where('id', $id)->update(['brand_logo' => null]);

        return redirect()->back()->with('success_message', 'Logo deleted successfully.');
    }
}
