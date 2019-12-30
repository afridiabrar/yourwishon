<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Product;

class ProductController extends Controller
{
    //
    protected $successStatus = 200;
    public function getProduct($id)
    {
        $product = Product::where('category_id', $id)->where('is_deleted', 0)->with('categories')->with('productImages')->paginate(10);
        if (count($product) > 0) {
            return response()->json(['status' => 'success', 'data' => $product, 'msg' => 'Data Found'], $this->successStatus);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Not Found'], $this->successStatus);
        }
    }

    public function getSingleProduct($id)
    {
        $data['product'] = Product::where('id', $id)->where('is_deleted', 0)->with('categories')->with('productImages')->first();
        if ($data['product']) {
            $data['related'] = Product::where('slug', $data['product']->slug)->with('categories')->with('productImages')->orderBy('id', 'DESC')->paginate(10);
            return response()->json(['status' => 'success', 'data' => $data, 'msg' => 'Data Found'], $this->successStatus);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Not Found'], $this->successStatus);
        }
    }

    public function getAllProduct()
    {
        $product = Product::with('categories')->where('is_deleted', 0)->with('productImages')->paginate(10);
        if ($product) {
            return response()->json(['status' => 'success', 'data' => $product, 'msg' => 'Data Found'], $this->successStatus);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Not Found'], $this->successStatus);
        }
    }

    public function search(Request $request)
    {
        $product = '';
        if (!empty($request->search)) {
            $product = Product::where('name', 'LIKE', '%' . $request->search . '%')->where('is_deleted', 0)->with('categories')->with('productImages')->paginate(6);
            if (!empty($product)) {
                return response()->json(['status' => 'success', 'data' => $product, 'msg' => 'Data Found'], $this->successStatus);
            } else {
                return response()->json(['status' => 'error', 'msg' => 'Not Found'], $this->successStatus);
            }
        }
    }
}
