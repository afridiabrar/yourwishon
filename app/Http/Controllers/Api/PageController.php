<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;

class PageController extends Controller
{
    //
    public $status = 200;
    public function showPages()
    {
        $page = Page::where('id', 1)->first();
        if ($page) {
            return response()->json(['status' => 'success', 'msg' => 'Pages Information file', 'data' => $page], $this->status);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Not Found'], $this->status);
        }
    }
}
