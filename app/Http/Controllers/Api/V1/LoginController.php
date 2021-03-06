<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Device;
use App\Setting;
use App\City;
use App\Basket;
use App\UserAddress;
use App\UserLogin;

class LoginController extends Controller
{

    public $public_path;

    public function __construct()
    {

        $this->public_path = 'files/users/';
        
    }

    public function login(Request $request)
    {
        
        $rules = [
            'phone' => 'required|regex:/(05)[0-9]{8}/',
            'password' => 'required|min:3|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);
            //return response()->json(['status'=>400,'data' => $error_arr]);
            return response()->json(['status'=>400,'errors' => $validator->errors()->all()]);
        }

        if ($user = Auth::attempt(['phone' => $request->phone, 'password' => $request->password ])) {

            $user = auth()->user();
            if($request->plateform):
                $user->plateform = $request->plateform;
                $user->save();
            endif;

            $this->manageDevices($request, auth()->user());

            $login = UserLogin::where('user_id',$user->id)->first();
            if($login){
                $login->logins_count = $login->logins_count + 1;
                $login->save();
            }else{
                $newLogin = new UserLogin();
                $newLogin->user_id = $user->id ;
                $newLogin->logins_count = 1 ;
                $newLogin->save();
            }


            if($request->playerId):

                $basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();

                if($basket && $basket->user_id == null):
                    $basket->user_id = $user->id;
                    $basket->save();
                endif;

            endif;

            $data =  json_decode(json_encode($user),true);
            $data  =array_filter($data, function($value){
               return isset($value);
           });

            if (!$user->is_active):

                return response()->json([
                    'status' => 200,
                    'message' => 'هذا الحساب غير مفعل',
                    'data' => $data
                ]);

            endif;

            if ($user->is_suspend ==1 ):
                $msg = 'تم حظر المستخدم بسبب : '.$user->suspend_reason .' برجاء التواصل مع الادارة على الرقم : '.setting()->getBody('support_no');
                return response()->json([
                    'status' => 402,
                    'message' => $msg,
                    'errors' => [$msg],
                ]);

            endif;

            if (!auth()->user()->api_token) {
                auth()->user()->api_token = str_random(60);
                auth()->user()->save();
            }

            $this->manageDevices($request, auth()->user());

            $user->photo = $user->image ? $request->root() . '/' . $this->public_path . $user->image :'' ;
            
            //$user->cityName = $user->city != null ? $user->city->name : '';
            $user->addresses = UserAddress::where('user_id',$user->id)->select('id as addressId','city','address')->get();

            $user->addresses->map(function ($q) {
                $city = City::where('id',$q->city)->select('name')->first();
                $q->cityName = $city ? $city->name : '' ;
            });
            $data =  json_decode(json_encode($user),true);
            $data  =array_filter($data, function($value){
               return isset($value);
           });

            return response()->json([
                'status' => 200,
                'data' => $data,
                
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'الهاتف او كلمة المرور غير صحيحة',
                'errors' => ['الهاتف او كلمة المرور غير صحيحة'],
            ]);
        }
    
    }

    public function renewPlayerId(Request $request){
        $user = auth()->user();

        if(! $user):
             return response()->json([
                'status' => 400,
                'errors' => ['مستخدم غير مسجل بالتطبيق'] ,
            ]);
        endif;
        $this->manageDevices($request, $user);
        return response()->json([
            'status' => 200,
        ]);
    }

    /**
     * @param $request
     * @@ User Device Management
     */
    private function manageDevices($request, $user = null)
    {

        if ($request->playerId) {
            //$devices = Device::where('device',$request->playerId)->toArray();
            
            $devices = Device::where('device',$request->playerId)->where('user_id',$user->id)->first();
            
            
            if ( !$devices) {
                $device = new Device;
                $device->device = $request->playerId;
                $device->user_id = $user->id;
                $device->save();
            }
            
            
            // if (in_array($request->playerId, $devices)) {
            //     $device = Device::where('device',$request->playerId)->first();
            //     $device->user_id = $user->id;
            //     $device->save();
            // }else {
            //     $device = new Device;
            //     $device->device = $request->playerId;
            //     $device->user_id = $user->id;
            //     $device->save();
            //     //$user->devices()->save($device);
            // }
        }
    }

    public function postActivationCode(Request $request)
    {        
        $rules = [
            'phone' => 'required|regex:/(05)[0-9]{8}/',
            'activation_code' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            //$error_arr = validateRules($validator->errors(), $rules);
            return response()->json(['status'=>400,'errors' => $validator->errors()->all()]);
        }

        $user = User::where(['phone' => $request->phone,
            'action_code' => $request->activation_code])
            ->first();
        

        if(! $user):
            return response()->json([
                'status' => 400,
                'message' => 'كود التفعيل غير صحيح',
                'errors' => ['كود التفعيل غير صحيح'],
            ]);
        endif;

        
                    
        if ($user->is_active == 0) {
            $user->is_active = 1;
            $user->update();      

            $user->photo = $user->image ? $request->root() . '/' . $this->public_path . $user->image :null ;
            $user->cityName = $user->city != null ? $user->city->name : null;

            $data =  json_decode(json_encode($user),true);
            $data  =array_filter($data, function($value){
               return isset($value);
           });
    
        $this->manageDevices($request, $user);          
            return response()->json([
                'status' => 200,
                'message' => 'تم تفعيل الحساب',
                'data' => $data
            ]);

        }

        $user->photo = $user->image ? $request->root() . '/' . $this->public_path . $user->image :'' ;
        $user->cityName = $user->city != null ? $user->city->name : '';

        $data =  json_decode(json_encode($user),true);
        $data  =array_filter($data, function($value){
           return isset($value);
       });
    
        $this->manageDevices($request, $user);

        return response()->json([
            'status' => 400,
            'message' => 'تم تفعيل الحساب من قبل',
            'errors' => ['تم تفعيل الحساب من قبل'],
            'data' => $data
        ]); 
    }

}
