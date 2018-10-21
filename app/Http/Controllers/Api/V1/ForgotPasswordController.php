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

    public function getActivationCodeApi($phone){
        
        // $validator = Validator::make($request->all(), [
        //     'phone' => 'required'
        // ]);

        // if ($validator->fails()):
        //     return response()->json([
        //         'status' => 400,
        //         //'message' => 'thisfieldrequired',
        //         'errors' => $validator->errors()->all()
        //     ]);
        // endif;
        
        $user = User::where('phone',$phone)->first();
        
        if (!$user) {
            return response()->json([
                'status' => 400,
                'message' => 'رقم الهاتف غير صحيح',
                'errors' => ['رقم الهاتف غير صحيح'],
            ]);
        }
        
        return response()->json([
                'status' => 200,
                //'message' =>$user->is_active ,
                'data' => ['activation_code' => $user->action_code]
            ]);
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
                'status' => 400,
                'errors' => $validator->errors()->all()
            ]);
        endif;

        $user = User::where('phone',$request->phone)->first();
        
        if (!$user) {
            return response()->json([
                'status' => 400,
                'message' => 'رقم الهاتف غير صحيح',
                'errors' =>[ 'رقم الهاتف غير صحيح'],
            ]);
        }

        $reset_code = rand(1000, 9999);
        
        $digits = 4;
        
        //$reset_code = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        
        $user->action_code = $user->actionCode($reset_code);
        
        $user->save();
        
        $phone = filter_mobile_number($user->phone);
        //$s = Sms::sendActivationCode('Reset code:' . $user->action_code, $phone);

        return response()->json([
                'status' => 200,
                'message' => 'تم الارسال',
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
