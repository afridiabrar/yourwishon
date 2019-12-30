<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\User;
use Hash;
use App\Address;
use App\BannerImage;
use App\Category;
use App\Country;
use App\Product;
use App\State;
use File;
use Image;

class UserController extends Controller
{
    //
    public function index()
    {
        $banner = BannerImage::where('status', 'Featured')->get();
        $category = Category::with('products')->where('status','Featured')->get();
        $product = Product::where('is_deleted', 0)->orderBy('id', 'DESC')->take(20)->get();
        return view('web.pages.index', ['banner' => $banner, 'category' => $category, 'product' => $product]);
    }
    public function account()
    {
        if (Auth::user()) {
            $user = Auth::user();
            $country = Country::get();
            return view('web.pages.my-account', ['user' => $user, 'country' => $country]);
        } else {
            session()->flash('error', 'Please Login First!');
            return \redirect(route('authentication'));
        }
    }

    public function change_password(Request $request)
    {
        $user = Auth::user();
        if (empty($user)) {
            session()->flash('error', 'Please Login First!');
            return redirect(route('authentication'));
        }
        $validator = Validator::make(
            $request->all(),
            [
                'old_password' => 'min:6',
                'password' => 'min:6|required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'min:6'
            ],
            [
                'password.required' => "Password is Required",
            ]
        );
        if ($validator->fails()) {
            session()->flash('error', implode("\n", $validator->errors()->all()));
            session()->flash('tab', 'address');
            return redirect()->back()->withInput();
        }
        $user = User::where('id', $user->id)->first();
        if ($user) {
            $CheckPass = Hash::check($request->old_password, $user->password);
            if ($CheckPass) {
                $new_password = $request->password;
                $user->password = Hash::make($new_password);
                $user->save();
                Auth::logout();
                session()->flash('error', 'Password Changed. Please Login Again!');
                return redirect()->back();
            } else {
                session()->flash('error', 'Old Password Not Match');
                session()->flash('tab', 'address');
                return redirect()->back();
            }
        } else {

            session()->flash('error', 'User Not Found!');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'mimes:jpeg,bmp,png|max:2000',
            ],
            [
                'image.size' => "Maximum file size to upload is 2MB (2024 KB). If you are uploading a photo, try to reduce its resolution to make it under 2MB",
            ]
        );

        if ($validator->fails()) {
            session()->flash('error', implode("\n", $validator->errors()->all()));
            return redirect()->back()->withInput();
        }
        $user = Auth::user();
        if (empty($user)) {
            session()->flash('error', 'Please Login First!');
            return redirect(route('authentication'));
        }
        if (isset($request->image)) {
            $image_path = $user->profile_pic;  // Value is not URL but directory file path
            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $originalImage = $request->file('image');
            $thumbnailImage = Image::make($originalImage);
            $originalPath = 'public/images/profile/';
            $thumbnailImage->save($originalPath . time() . $originalImage->getClientOriginalName());
            $thumbnailImage->resize(150, 150);
            $update['profile_pic'] = $originalPath . time() . $originalImage->getClientOriginalName();
            $update['picture_type'] = 'Local';
        }
        $update['f_name'] = $request->f_name;
        $update['l_name'] = $request->l_name;
        $update['phone_no'] = $request->phone_no;
        $update['gender'] = $request->gender;
        $country = Country::find($request->country);
        if ($country) {
            $update['country'] = $country->name;
            $update['state'] = $request->state;
        }

        $update['zip'] = $request->zip;
        $user = User::where('id', $user->id)->update($update);
        if ($user) {
            session()->flash('success', 'Profile Updated!');
            return redirect()->back();
        } else {
            session()->flash('error', 'User Not Found!');
            return redirect()->back();
        }
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $updatee['default_address'] = 0;
        $update = Address::where('user_id', $user->id)->update($updatee);
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'address1' => 'required',
                'postcode' => 'required',
                'city' => 'required',
                'phone' => 'required'
            ],
            [
                'first_name.required' => "First Name is Required",
                'last_name.required' => "Last Name is Required",
                'address1.required' => "Address is Required",
                'postcode.required' => "Post Code is Required",
                'phone.required' => "Phone No is Required",
            ]
        );

        if ($validator->fails()) {
            session()->flash('error', implode("\n", $validator->errors()->all()));
            session()->flash('tab', 'lg2');
            return redirect()->back()->withInput();
        }

        $request['user_id'] = $user->id;
        $request['default_address'] = 1;
        unset($request['_token']);
        $address = Address::create($request->all());
        if ($address) {
            session()->flash('success', 'Address Has Been Added!');
            return redirect()->back();
        } else {
            session()->flash('error', 'Please Try Again Later!');
            return redirect()->back();
        }
    }

    public function changeState(Request $request)
    {
        $state = State::where('country_id', $request->id)->get();
?>
        <?php foreach ($state as $k => $v) { ?>
            <option value="<?= $v->name ?>"><?= $v->name ?></option>
        <?php } ?>
<?php
                                    }
                                }
