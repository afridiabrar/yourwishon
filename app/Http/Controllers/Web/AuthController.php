<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Hash;
use App\User;
use Auth;
use Mail;
use App\Mail\RegisterMail;
use App\Page;

class AuthController extends Controller
{
    //
    public function authentication()
    {
        if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }
        return view('web.auth.auth');
    }


    public function registerUser(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'f_name' => 'required|string|max:255',
                'l_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'min:6|required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'min:6'
            ],
            [
                'f_name.required' => "First Name is Required",
                'l_name.required' => "Last Name is Required",
                'email.required' => "Email is Required",
                'email.email' => "Email Is Badly Formated",
                'password.required' => "Password is Required",
            ]
        );

        if ($validator->fails()) {
            session()->flash('error', implode("\n", $validator->errors()->all()));
            session()->flash('tab', 'lg2');
            return redirect()->back()->withInput();
        }

        $request['password'] = Hash::make($request['password']);
        $request['account_type'] = 'local';
        $request['is_admin'] = 0;

        $user = User::create($request->all());
        if ($user) {
            $this->RegisterMessage($user);
            session()->flash('success', 'Now You Can Login!');
            return redirect()->back();
        } else {
            session()->flash('success', 'Some Error Occured');
            return redirect()->back();
        }
    }

    public function RegisterMessage(User $user)
    {
        return Mail::to($user)->send(new RegisterMail($user));
    }

    public function doLogin(Request $request)
    {

        $remember_me = $request->has('remember_me') ? true : false;
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'min:6|required'
            ],
            [
                'email.required' => "Email is Required",
                'email.email' => "Email Is Badly Formated",
                'password.required' => "Password is Required",
            ]
        );
        if ($validator->fails()) {
            session()->flash('error', implode("\n", $validator->errors()->all()));
            return redirect()->back()->withInput();
        }

        $remember_me = $request->has('remember') ? true : false;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_admin' => 0, 'status' => 'Activate'], $remember_me)) {
            if (Auth::user()) {
                User::find(Auth::id());
                return redirect('/index');
            } else {
                $user = User::find(Auth::id());
                session()->flash('success', 'Invalid Credentials!');
                return redirect()->back();
            }
        }
        session()->flash('error', 'Invalid Credentials!');
        return redirect()->back();
    }

    public function logout()
    {
        Auth::logout();
        session()->flash('success', 'Logout Successfully!');
        return redirect()->back();
    }

    public function show_term(Request $request)
    {
        $user = Page::find(1);
        //         $user->disclaimer
?>
        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 650px;" role="document">
                <div class="modal-content" style="font-size: 13px;line-height: 20px;height: 500px;">
                    <div class="modal-header text-center" style="padding: 0.5rem;">
                        <h5 class="modal-title" style=" width: 100%" id="exampleModalLongTitle">Term & Condition</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- <iframe style="width: 100%;height: 100%;border: 0" src="https://app.termly.io/document/disclaimer/69602267-0857-4ba7-9a43-163d05972af3"></iframe> -->
                        <p><?= $user->term_condition ?> </p>
                    </div>
                    <div class="modal-footer" style="padding: 0.5rem;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Okay</button>
                    </div>
                </div>
            </div>
        </div>
<?php
                        }
                    }
