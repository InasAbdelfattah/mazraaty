<?php

namespace App\Http\Controllers\Api\V1;

use App\Category;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Device;
use App\Setting;
use App\Order;
use App\City;


class LoginController extends Controller
{

    public $public_path_user;

    public function __construct()
    {

        $this->public_path_user = 'files/users/';
        
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
            return response()->json(['status'=>false,'data' => $error_arr]);
            //return response()->json(['status'=>false,'data' => $validator->errors()->all()]);
        }

        if ($user = Auth::attempt(['phone' => $request->phone, 'password' => $request->password ])) {

            $user = auth()->user();

            if (!$user->is_active):

                return response()->json([
                    'status' => false,
                    'message' => 'هذا الحساب غير مفعل',
                    'data' => $user
                ], 401);

            endif;

            if (!auth()->user()->api_token) {
                auth()->user()->api_token = str_random(60);
                auth()->user()->save();
            }

            $this->manageDevices($request, auth()->user());

            if($user->image != ''){
                $user->photo = $request->root() . '/' . $this->public_path_user . $user->image ;
            }           
            $user_city = City::where('id',$user->city)->first();
            
            if($user_city):
                $user->user_city->name = $user->user_city->name;
            endif;
            
            return response()->json([
                'status' => true,
                'data' => $user,
                
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'الهاتف او كلمة المرور غير صحيحة',
                'data' => null
            ], 400);
        }
    
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
}
