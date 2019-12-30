<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Query;

class QueryController extends Controller
{
    //
    protected $successStatus = 200;

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'phone_no' => 'required',
                'message' => 'required',

            ],
            [
                'name.required' => "Full Name is Required",
                'phone_no.required' => "Phone Number is Required",
                'email.required' => "Email is Required",
                'email.email' => "Email Is Badly Formated",
                'message.required' => "Message is Required",
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->errors()->all()], $this->successStatus);
        }

        $request['query_type'] = 'Contact';
        $request['user_id'] = (!empty($user)) ? $user->id : NULL;
        $user = Query::create($request->all());
        if ($user) {
            return response()->json(['status' => 'success', 'msg' => 'Your Message Has Been Sent!'], $this->successStatus);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Some Error Occured'], $this->successStatus);
        }
    }
}
