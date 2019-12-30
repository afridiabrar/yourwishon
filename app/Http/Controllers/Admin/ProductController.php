<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use Validator;
use App\ProductImage;
use File;
use Image;


class ProductController extends Controller
{
    //
    public function show()
    {
        $product = Product::with('productImages')->where('is_deleted', 0)->with('categories')->orderBy('id', 'Desc')->paginate(10);
        return view('admin.pages.product', ['product' => $product]);
    }

    public function addPopup()
    {
        $category = Category::get();
        return view('admin.popup.add-product', ['category' => $category]);
    }

    public function delete_product($id)
    {
        $product = Product::find($id);
        if ($product) {
            $update = Product::where('id', $id)->update(['is_deleted' => 1]);
            session()->flash('success', 'Product Has Been Deleted');
            return redirect()->back();
        } else {
            session()->flash('error', 'Not Found!');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'qty' => 'required',
                'price' => 'required',
                'cost_price' => 'required',
                'image' => 'mimes:jpeg,bmp,png|max:2000',
                'size' => 'required',
                'category_id' => 'required',

            ],
            [
                'image.max' => "Maximum file size to upload is 2MB (2024 KB). If you are uploading a photo, try to reduce its resolution to make it under 2MB",
            ]
        );
        if ($validator->fails()) {
            session()->flash('error', implode("\n", $validator->errors()->all()));
            return redirect()->back()->withInput();
        }

        $catData = Category::find($request->category_id);
        if (empty($catData)) {
            session()->flash('error', 'Select Category First!');
            return redirect()->back()->withInput();
        }
        $product['name'] = $request->name;
        $product['qty'] = $request->qty;
        $product['in_stock'] = $request->qty;
        $product['price'] = $request->price;
        $product['cost_price'] = $request->cost_price;
        // $product['color'] = $request->color;
        $product['size'] = $request->size;
        $product['slug'] = $catData->slug;
        $product['category_id'] = $catData->id;
        $product['label'] = ($request->label) ? $request->label : NULL;
        $product['description'] = ($request->description) ? $request->description : NULL;
        $product['weight'] = ($request->weight) ? $request->weight : NULL;
        $product['width'] = ($request->width) ? $request->width : NULL;
        $product['height'] = ($request->height) ? $request->height : NULL;
        $product['length'] = ($request->length) ? $request->length : NULL;
        $featured_path = 'public/images/featured/';
        $featured_image = upload_image($request, $featured_path);
        if (isset($featured_image)) {
            $product['featured_image'] = $featured_path . $featured_image['data'];
        }
        $product = Product::create($product);
        if ($product) {
            // $type = '';
            // $featured_path = 'public/images/product/';
            // $images = $request->file('prouct_images');
            // if ($images) {
            //     foreach ($images as $image) {
            //         $type = ($image->getMimeType() == 'video/mp4') ? 'video' : 'image';
            //         $new_name = rand() . '.' . $image->getClientOriginalExtension();
            //         $image->move(public_path('images/product'), $new_name);
            //         $p_image['product_id'] = $product->id;
            //         $p_image['type'] = $type;
            //         $p_image['prouct_images'] = $featured_path . $new_name;
            //         ProductImage::create($p_image);
            //     }
            // }

            $type = '';
            $originalImage = $request->file('prouct_images');

            $featured_path = 'public/images/product/';
            $imageName = '';
            if ($originalImage) {
                foreach ($originalImage as $image) {
                    $type = ($image->getMimeType() != 'video/x-ms-wmv' || $image->getMimeType() == 'video/webm' || $image->getMimeType() == 'video/mp4'  || $image->getMimeType() == 'video/mov' || $image->getMimeType() == 'video/3gp') ? 'video' : 'image';
                    if ($type == 'video') {
                        $new_name = time() . "." . $image->getClientOriginalExtension();
                        $image->move(public_path('/images/product'), $new_name);
                        $imageName = $featured_path . $new_name;
                    } else {
                        $thumbnailImage = Image::make($image);
                        $originalPath = 'public/images/product/';
                        $thumbnailImage->save($originalPath . time() . $image->getClientOriginalName());
                        $thumbnailImage->resize(150, 150);
                        $imageName = $featured_path . time() . $image->getClientOriginalName();
                    }
                    $p_image['product_id'] = $product->id;
                    $p_image['type'] = $type;
                    $p_image['prouct_images'] = $imageName;
                    ProductImage::create($p_image);
                }
            }
        }
        session()->flash('success', 'Product Uploaded Successfulyy!');
        return redirect()->back();
        // $output = array(
        //     'success'  => 'Images uploaded successfully',
        //     'image'   => $image_code
        // );
        // return response()->json($output);
    }


    public function view_product($id)
    {
        $product = Product::where('id', $id)->with('categories')->with('productImages')->first();
        return view('admin.pages.view-product', ['product' => $product]);
    }

    public function edit_product($id)
    {
        $product = Product::where('id', $id)->with('categories')->with('productImages')->first();
        return view('admin.pages.edit_product', ['product' => $product]);
    }

    public function viewPaymentPopup()
    {
        return view('admin.popup.view-product');
    }
    public function editPaymentPopup()
    {
        return view('admin.popup.edit-product');
    }


    public function changeproductStatus($id, $status)
    {

        $user = Product::find($id);
        $user->is_featured = $status;
        if ($user->save()) {
            session()->flash('success', 'Status Updated Successfully');
            return redirect()->back();
        } else {
            session()->flash('error', 'Data Not Found');
            return redirect()->back();
        }
    }

    public function UpdateProduct(Request $request, $id)
    {
        $product_data = Product::find($id);
        if ($product_data) {

            if (!empty($request->image)) {
                $image_path = $product_data->featured_image;  // Value is not URL but directory file path
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                $featured_path = 'public/images/featured/';
                $featured_image = upload_image($request, $featured_path);
                if (isset($featured_image)) {
                    $request['featured_image'] = $featured_path . $featured_image['data'];
                }
            }

            $data = $request->except('_token', 'image');
            $data['qty'] = $product_data->qty + $request->qty;
            $data['in_stock'] = $product_data->in_stock  + $request->qty;

            $product = Product::where('id', $id)->update($data);
            if ($product) {
                session()->flash('success', 'Product Uploaded Successfulyy!');
                return redirect()->back();
            } else {
                session()->flash('error', 'Error Occured');
                return redirect()->back();
            }
        } else {
            session()->flash('error', 'Not Found');
            return redirect()->back();
        }
    }

    public function deleteImages($id)
    {
        $image = ProductImage::findOrFail($id);

        $image_path = $image->prouct_images;  // Value is not URL but directory file path
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        $image->delete();
        session()->flash('success', 'Deleted Succ');
        return redirect()->back();
    }

    public function addImageGaleryPopup($id)
    {

        $image = Product::find($id);
        if ($image) {
            return view('admin.popup.add-galery', ['product_image' => $image]);
        } else {
            echo "Not Found";
        }
    }

    // public function addImage(Request $request)
    // {

    //     $type = '';
    //     $featured_path = 'public/images/product/';
    //     $images = $request->file('prouct_images');
    //     if ($images) {
    //         foreach ($images as $image) {
    //             $type = ($image->getMimeType() == 'video/mp4') ? 'video' : 'image';
    //             $new_name = rand() . '.' . $image->getClientOriginalExtension();
    //             $image->move(public_path('images/product'), $new_name);
    //             $p_image['product_id'] = $request->product_id;
    //             $p_image['type'] = $type;
    //             $p_image['prouct_images'] = $featured_path . $new_name;
    //             ProductImage::create($p_image);
    //         }
    //     }
    //     session()->flash('success', 'Galery Images Added');
    //     return redirect()->back();
    // }


    public function addImage(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'prouct_images' => 'required'
            ]
        );
        if ($validator->fails()) {
            session()->flash('error', implode("\n", $validator->errors()->all()));
            return redirect()->back()->withInput();
        }
        $type = '';
        $originalImage = $request->file('prouct_images');
        $featured_path = 'public/images/product/';
        $imageName = '';
        if ($originalImage) {
            foreach ($originalImage as $image) {
                $type = ($image->getMimeType() == 'video/webm' || $image->getMimeType() == 'video/mp4' || $image->getMimeType() == 'video/mov' || $image->getMimeType() == 'video/3gp') ? 'video' : 'image';
                if ($type == 'video') {
                    $new_name = rand() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('/images/product'), $new_name);
                    $imageName = $featured_path . $new_name;
                } else {
                    $thumbnailImage = Image::make($image);
                    $originalPath = 'public/images/product/';
                    $thumbnailImage->save($originalPath . time() . $image->getClientOriginalName());
                    $thumbnailImage->resize(150, 150);
                    $imageName = $featured_path . time() . $image->getClientOriginalName();
                }
                $p_image['product_id'] = $request->product_id;
                $p_image['type'] = $type;
                $p_image['prouct_images'] = $imageName;
                ProductImage::create($p_image);
            }
        }
        session()->flash('success', 'Galery Images Added');
        return redirect()->back();
    }
}
