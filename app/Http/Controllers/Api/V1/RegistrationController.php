<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\NotifyAdminJoinApp;
use App\Events\NotifyUsers;
use App\Notifications\NotifyAdminForJoinCompanies;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
 

class RegistrationController extends Controller
{

    public $public_path;

    public function __construct()
    {
        app()->setlocale(request('lang'));
        
        $this->public_path = 'files/users/';
        
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|min:3|max:255',
            'phone' => 'required|regex:/(05)[0-9]{8}/|unique:users,phone',
            'password' => 'required|confirmed',
            'city_id' => 'required',
            
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);
            return response()->json(['status'=>false,'data' => $error_arr , 'message'=>'يرجى استكمال البيانات والتأكد من ادخال محتوى صالح']);
            //return response()->json(['status'=>false,'data' => $validator->errors()->all()]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email ? $request->email : '';
        $user->phone = trim($request->phone);
        $user->password = trim($request->password);
        $user->api_token = str_random(60);
        $user->city_id = $request->city_id ;
        $user->is_admin = 0;
        $actionCode = rand(1000, 9999);
        $actionCode = $user->actionCode($actionCode);
        $user->action_code = $actionCode;
        $user->is_active = 0;
        $user->is_suspend = 0;
        $user->is_new = 0;

        if($user->save()){
                //send sms to user with activation code
            $phone = filter_mobile_number($user->phone);
            sendSms('activation code:'.$user->action_code , $phone);

            return response()->json([
                'status' => true,
                'data' => $user,
            ]);
        }

        return response()->json([
                'status' => false,
                'data' => null,
                'msg' => 'لم يكتمل التسجيلز يرجى المحاولة مرة أخرى'
            ]);
    }

}
