<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Product;

class CategoryController extends Controller
{
    //
    protected $successStatus = 200;
    public function getCategory()
    {
        $cat = Category::paginate(10);

        if (count($cat) > 0) {
            return response()->json(['status' => 'success', 'data' => $cat, 'msg' => 'Data Found'], $this->successStatus);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Not Found'], $this->successStatus);
        }
    }
}
