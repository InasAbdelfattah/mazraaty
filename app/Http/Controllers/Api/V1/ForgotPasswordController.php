<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Transformers\Json;
use App\User;
use Illuminate\Http\Request;
//use Sms;

use Validator;

class ForgotPasswordController extends Controller
{

    public function getActivationCodeApi(Request $request){
        
        $validator = Validator::make($request->all(), [
            'phone' => 'required'
        ]);

        if ($validator->fails()):
            return response()->json([
                'status' => false,
                'message' => 'thisfieldrequired',
                'data' => $validator->errors()->first()
            ], 400);
        endif;
        
        $user = User::where('phone',$request->phone)->first();
        
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'phoneincorrect',
                'data' => null
            ], 400);
        }
        
        return response()->json([
                'status' => true,
                'message' =>$user->is_active ,
                'data' => $user->action_code
            ], 200);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function getResetTokens(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone' => 'required|regex:/(05)[0-9]{8}/'
        ]);

        if ($validator->fails()):
            return response()->json([
                'status' => false,
                'message' => 'thisfieldrequired',
                'data' => $validator->errors()
            ], 400);
        endif;

        $user = User::where('phone',$request->phone)->first();
        
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'phoneincorrect',
                'data' => null
            ], 400);
        }

        $reset_code = rand(1000, 9999);
        
        $digits = 4;
        
        //$reset_code = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        
        $user->action_code = $user->actionCode($reset_code);
        
        $user->save();
        
        $phone = filter_mobile_number($user->phone);
        //$s = Sms::sendActivationCode('Reset code:' . $user->action_code, $phone);

        return response()->json([
                'status' => true,
                'message' => 'resetcodesent',
                 'data' => [
                    'reset_code' => $user->action_code,
                ]
                
            ]
        );

    }


    public function resendResetPasswordCode(Request $request)
    {
        return $this->getResetTokens($request);
    }

}
