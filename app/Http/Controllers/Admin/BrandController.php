<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BrandController extends Controller
{
    public function brands()
    {
        Session::put('page', 'brands');
        $brands = Brand::all();

        return view('admin.brands.brands', compact('brands'));
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
        return redirect()->back()->with('success_message', 'Category deleted successfully.');
    }
}
