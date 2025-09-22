<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function products()
    {
        $products = Product::get()->toArray();

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

    public function deleteProduct($id)
    {
        Product::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Product deleted successfully.');
    }
}
