<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\Json;
use App\User;

use Validator;

class ResetPasswordController extends Controller
{

    public function reset(Request $request)
    {

        $rules = [
            'reset_code' => 'required',
            'phone' => 'required|regex:/(05)[0-9]{8}/',
            'password' => 'required|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);
            return response()->json(['status'=>false,'data' => $error_arr]);
        }

        $user = User::whereActionCode($request->reset_code)->where('phone', $request->phone)
            ->first();
        if ($user) {
            $user->password = bcrypt(trim($request->password));
            $user->save();
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Password Reset Successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Reset Code or phone is invalid.',
            ]);
        }
    }


    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'reset_code' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $code = User::whereActionCode($request->reset_code)
            ->first();
        if ($code) {
            return response()->json([
                'status' => true,
                'message' => 'activationSuccess'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'activationError',
            ]);
        }
    }


    public function checkCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'activation_code' => 'required']
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = User::where(['action_code' => $request->activation_code])->first();

        if ($user) {

            $user->phone = $request->phone;
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'code and phone correct',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'code or phone incorrect'
            ]);
        }
    }

}
