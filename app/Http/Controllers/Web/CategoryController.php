<?php

namespace App\Http\Controllers\Web;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;

class CategoryController extends Controller
{
    //

    public function index()
    {

        $cat = Category::get();
        $product = Product::with('categories')->where('is_deleted', 0)->with('productImages')->orderBy('id', 'DESC')->paginate(9);
        return view('web.pages.category', ['category' => $cat, 'product' => $product]);
    }

    public function FilterCategory($slug)
    {
        $cat = Category::get();
        $product = Product::where('slug', $slug)->where('is_deleted', 0)->with('categories')->with('productImages')->orderBy('id', 'DESC')->paginate(9);
        return view('web.pages.category', ['category' => $cat, 'product' => $product]);
    }

    public function productDetail($id)
    {
        $product = Product::where('id', $id)->with('categories')->with('productImages')->first();
        $related = Product::where('slug', $product->slug)->with('categories')->with('productImages')->orderBy('id', 'DESC')->paginate(9);
        return view('web.pages.product-detail', ['product' => $product, 'related' => $related]);
    }
}
