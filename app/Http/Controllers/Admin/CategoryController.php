<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Validator;

class CategoryController extends Controller
{
    //

    public function addPopup(Request $request)
    {
        return view('admin.popup.add-category');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:categories',
            ],
            [
                'name.required' => "Category Name is Already Exits",
            ]
        );
        if ($validator->fails()) {
            session()->flash('error', implode("\n", $validator->errors()->all()));
            session()->flash('tab', 'lg2');
            return redirect()->back()->withInput();
        }
        $request['slug'] = str_slug($request->name, '-');
        $icon = 'public/images/icon/';
        $image = upload_image($request, $icon);
        if (isset($image)) {
            $request['icon'] = $icon . $image['data'];
        }
        $user = Category::create($request->all());
        if ($user) {
            session()->flash('success', 'Now You Can Login!');
            return redirect()->back();
        } else {
            session()->flash('error', 'Some Error Occured');
            return redirect()->back();
        }
    }

    public function changeCategorystatus($id, $status)
    {

        $user = Category::find($id);
        $user->status = $status;
        if ($user->save()) {
            session()->flash('success', 'Status Updated Successfully');
            return redirect()->back();
        } else {
            session()->flash('error', 'Data Not Found');
            return redirect()->back();
        }
    }
}
