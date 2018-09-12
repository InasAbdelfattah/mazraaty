<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Validator;
use App\UserAddress;
use App\Device;

class UsersController extends Controller
{

    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/users/';  
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $user = User::whereApiToken($request->api_token)->first();
        $user->photo = $user->image ? $request->root() . '/' . $this->public_path . $user->image :null ;
        $user->cityName = $user->city != null ? $user->city->name : null;
        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }


    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        if(! $user):
             return response()->json([
                'status' => false,
                'message' => 'مستخدم غير مسجل بالتطبيق' ,
                'data' => null
            ]);
        endif;

        $rules = [
            'name' => 'min:3',
            'phone' => 'regex:/(05)[0-9]{8}/|unique:users,phone,'.$user->id,
            //'email' => 'required|email|unique:users,email,'.$user->id,
            'email' => 'nullable|email|unique:users,email,'.$user->id,
            'password' => 'confirmed',
            'image' =>'mimes:png,jpg,jpeg'
            
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);
            return response()->json(['status'=>false,'data' => $error_arr , 'message'=>'يرجى ادخال بيانات صحيحة']);
            //return response()->json(['status'=>false,'data' => $validator->errors()->all()]);
        }

        if($request->has('name') && $request->name != ''):
            $user->name = $request->name;
        endif;

         if($request->has('email') && $request->email != ''):
                $email_check = User::where('email',$request->email)->first();
                if($email_check && $email_check->id != $user->id):
                    if(app()->getlocale() == 'ar'):
                        $msg = 'البريد الالكترونى مستخدم من قبل';
                    else:
                        $msg = 'the email has been used before';
                    endif;
                    return response()->json([
                        'status' => false,
                        'data' => $msg,
                    ]);
                endif;
                
                $user->email = $request->email;
            endif;

            if($request->has('phone') && $request->phone != ''):
                $phone_check = User::where('phone',$request->phone)->first();
                if($phone_check && $phone_check->id != $user->id):
                    if(app()->getlocale() == 'ar'):
                        $msg = ' الهاتف مستخدم من قبل';
                    else:
                        $msg = 'the phone has been used before';
                    endif;
                    return response()->json([
                        'status' => false,
                        'data' => $msg,
                    ]);
                endif;

                $user->phone = $request->phone;
                $reset_code = rand(1000, 9999);
                $user->is_active =0;
                $user->action_code = $reset_code;
                sendSms('activation code:'.$user->action_code , $user->phone);
                
            endif;

        if ($request->hasFile('image')):
            $user->image = uploadImage($request, 'image', $this->public_path_user);
        endif;
            
        if($request->has('city_id') && $request->city_id != ''):
            $user->city_id = $request->city_id;
        endif;
        
        $user->save();
        
        $user->photo = $user->image ? $request->root() . '/' . $this->public_path . $user->image :null ;
        $user->cityName = $user->city != null ? $user->city->name : null;

        if($request->has('addresses')){
            $addresses = json_decode($request->addresses);
            //dd($addresses);
            if(count($addresses) > 0){
                $old_addresses = UserAddress::where('user_id',$user->id)->get();
                    if(count($old_addresses) > 0){
                        foreach($old_addresses as $old){
                            $old->delete();
                        }
                    }
                foreach($addresses as $day){
                    $model = new UserAddress;
                    $model->user_id = $user->id;
                    $model->address = $day->address;
                    $model->lat = $day->lat;
                    $model->lng = $day->lng;
                    $model->save();
                }
            }
        }
                
        return response()->json([
            'status' => true,
            'data' => $user,
            'code' => $user->action_code
        ]);

    }

    public function changePassword(Request $request)
    {

        $user = User::whereApiToken($request->api_token)->first();
        
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required|confirmed',
            //'newPassword_confirmation' => 'required|same:newpassword',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
            ]);
        }
        if (Hash::check($request->oldPassword, $user->password)) {
            //Change the password
            $user->fill([
                'password' => Hash::make($request->newPassword)
            ])->save();

            return response()->json([
                'status' => true,
                'message' => 'لقد تم تعديل كلمة المرور بنجاح' ,
                'data' => null
            ]);
        } else {
            
            return response()->json([
                'status' => false,
                'message' => 'كلمة المرور القديمة غير صحيحة' ,
                'data' => null
            ]);
        }
    }

    public function getUserById($id){
        $user = User::where('id',$id)->select('id','name','phone','gender','image')->first();
        if(! $user){
            return response()->json([
                'status' => false,
                'message' => 'مستخدم غير موجود',
                'data' => null
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'تم',
            'data' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $user = User::where('api_token', $request->api_token)->first();
        if(!$user){
            return response()->json([
                'status' => false,
            ]);
        }

        $device = Device::where('device',$request->playerId)->where('user_id',$user->id)->first();
        if($device):
            $device->delete();
            return response()->json([
                'status' => true,
            ]);
        
        else :
            return response()->json([
                'status' => false,
            ]);

        endif;
    }

    /**
     * @param $request
     * @return array
     */
    private function postData($request)
    {
        return [
            'username' => $request->username,
            'phone' => $request->user_phone,
            'email' => $request->user_email,
        ];
    }

    /**
     * @return array
     */
    private function valRules($id)
    {
        return [
            //'username' => 'required',
            'phone' => 'regex:/(05)[0-9]{8}/|unique:users,phone,' . $id,
            'password' =>'confirmed'
        ];
    }

    /**
     * @return array
     */
    private function valMessages()
    {
        return [
            //'phone.required' => trans('global.field_required'),
            'phone.unique' => trans('global.unique_phone'),
            //'password.required' => trans('global.field_required'),
            //'password_confirmation.required' => trans('global.field_required'),
            'password_confirmation.same' => trans('global.password_not_confirmed'),
        ];
    }

    
}
