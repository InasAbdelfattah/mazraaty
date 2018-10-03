<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Validator;
use App\UserAddress;
use App\Device;
use App\City;

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

        $user->photo = $user->image ? $request->root() . '/' . $this->public_path . $user->image :'' ;
        $user->cityName = $user->city != null ? $user->city->name : '';

        $user->addresses = UserAddress::where('user_id',$user->id)->select('city','address')->get();

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
            'data' => $data
        ]);
    }

    public function getUserAddresses(Request $request){

        $user = auth()->user();

        if(! $user):
             return response()->json([
                'status' => 400,
                'errors' => ['مستخدم غير مسجل بالتطبيق'] ,
            ]);
        endif;

        $user_addresses = UserAddress::where('user_id',$user->id)->select('id','city','address')->get();

        $user_addresses->map(function ($q) {
            $city = City::where('id',$q->city)->select('name')->first();
            $q->cityName = $city ? $city->name : '' ;
            $q->cityId = $city ? $city->id : '' ;
        });

        $addresses = $user_addresses->map(function($q){
               $data = json_decode(json_encode($q),true);
              return $q= array_filter($data,function($value){
                   return isset($value);
               });
           });

        return response()->json([
            'status' => 200,
            'data' => $addresses
        ]);
    }

    public function updateAddress(Request $request)
    {
        $user = auth()->user();

        if(! $user):
             return response()->json([
                'status' => 400,
                'message' => 'مستخدم غير مسجل بالتطبيق' ,
                'errors' => ['مستخدم غير مسجل بالتطبيق'] ,
            ]);
        endif;

        $rules = [
            'address' => 'min:3',
            'cityId' => 'required',
            'addressId' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);
            return response()->json(['status'=>400,'errors' => $validator->errors()->all() , 'message'=>'يرجى ادخال بيانات صحيحة']);
        }

        $model = UserAddress::find($request->addressId);

        if(!$model):
            return response()->json([
                'status' => 400,
                'message' => 'عنوان غير مسجل' ,
                'errors' => ['عنوان غير مسجل'] ,
            ]);
        endif;

        $model->user_id = $user->id;
        $model->address = $request->address;
        $model->city = $request->cityId;
        $model->lat = $request->lat ? $request->lat : '';
        $model->lng = $request->lng ? $request->lng : '';
        $model->save();

        $addresses = UserAddress::where('user_id',$user->id)->select('id','city','address')->get();
   
        return response()->json([
            'status' => 200,
            'data' => $addresses,
        ]);

    }

    public function createAddress(Request $request)
    {
        $user = auth()->user();

        if(! $user):
             return response()->json([
                'status' => 400,
                'message' => 'مستخدم غير مسجل بالتطبيق' ,
                'errors' => ['مستخدم غير مسجل بالتطبيق'] ,
            ]);
        endif;

        $rules = [
            'address' => 'min:3',
            'cityId' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);
            return response()->json(['status'=>400,'errors' => $validator->errors()->all() , 'message'=>'يرجى ادخال بيانات صحيحة']);
        }

        $model = new UserAddress ;

        $model->user_id = $user->id;
        $model->address = $request->address;
        $model->city = $request->cityId;
        $model->lat = $request->lat ? $request->lat : '';
        $model->lng = $request->lng ? $request->lng : '';
        $model->save();

        $addresses = UserAddress::where('user_id',$user->id)->select('id','city','address')->get();
   
        return response()->json([
            'status' => 200,
            'data' => $addresses,
        ]);

    }

    public function deleteAddress(Request $request)
    {
        $user = auth()->user();

        if(! $user):
             return response()->json([
                'status' => 400,
                'message' => 'مستخدم غير مسجل بالتطبيق' ,
                'errors' => ['مستخدم غير مسجل بالتطبيق'] ,
            ]);
        endif;

        $rules = [
            'addressId' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);
            return response()->json(['status'=>400,'errors' => $validator->errors()->all() , 'message'=>'يرجى ادخال بيانات صحيحة']);
        }

        $model = UserAddress::where('id',$request->addressId)->where('user_id',$user->id)->first() ;

        if(!$model):
            return response()->json([
                'status' => 400,
                'message' => 'عنوان غير مسجل' ,
                'errors' => ['عنوان غير مسجل'] ,
            ]);
        endif;

        $model->delete();

        $addresses = UserAddress::where('user_id',$user->id)->select('id','city','address')->get();
   
        return response()->json([
            'status' => 200,
            'data' => $addresses,
        ]);

    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        if(! $user):
             return response()->json([
                'status' => 400,
                'message' => 'مستخدم غير مسجل بالتطبيق' ,
                'errors' => ['مستخدم غير مسجل بالتطبيق'] ,
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
            return response()->json(['status'=>400,'errors' => $validator->errors()->all() , 'message'=>'يرجى ادخال بيانات صحيحة']);
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
                        'status' => 400,
                        'errors' => [$msg],
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
                        'status' => 400,
                        'errors' => [$msg],
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
            
        if($request->has('cityId') && $request->cityId != ''):
            $user->city_id = $request->cityId;
        endif;
        
        $user->save();
        
        $user->photo = $user->image ? $request->root() . '/' . $this->public_path . $user->image :'' ;
        $user->cityName = $user->city != null ? $user->city->name : '';

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
                foreach($addresses as $address){
                    $model = new UserAddress;
                    $model->user_id = $user->id;
                    $model->address = $address->address;
                    $model->city = $address->cityId;
                    $model->lat = $address->lat ? $address->lat : '';
                    $model->lng = $address->lng ? $address->lng : '';
                    $model->save();
                }
            }
        }

        $user->addresses = UserAddress::where('user_id',$user->id)->select('city','address')->get();

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
            //'code' => $user->action_code
        ]);

    }

    public function changePassword(Request $request)
    {

        $user = User::whereApiToken($request->api_token)->first();
        
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required',
            //'newPassword_confirmation' => 'required|same:newpassword',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->all(),
            ]);
        }
        if (Hash::check($request->oldPassword, $user->password)) {
            //Change the password
            $user->fill([
                'password' => Hash::make($request->newPassword)
            ])->save();

            return response()->json([
                'status' => 200,
                'message' => 'لقد تم تعديل كلمة المرور بنجاح' ,
            ]);
        } else {
            
            return response()->json([
                'status' => 400,
                'message' => 'كلمة المرور القديمة غير صحيحة' ,
                'errors' => ['كلمة المرور القديمة غير صحيحة'] ,
            ]);
        }
    }

    public function getUserById($id){
        $user = User::where('id',$id)->select('id','name','phone','gender','image')->first();
        if(! $user){
            return response()->json([
                'status' => 400,
                'message' => 'مستخدم غير موجود',
                'errors' => ['مستخدم غير موجود'],
            ]);
        }

        $data =  json_decode(json_encode($user),true);
        $data  =array_filter($data, function($value){
           return isset($value);
       });

        return response()->json([
            'status' => 200,
            'message' => 'تم',
            'data' => $data
        ]);
    }

    public function logout(Request $request)
    {
        $user = User::where('api_token', $request->api_token)->first();
        if(!$user){
            return response()->json([
                'status' => 400,
            ]);
        }

        $device = Device::where('device',$request->playerId)->where('user_id',$user->id)->first();
        if($device):
            $device->delete();
            return response()->json([
                'status' => 200,
            ]);
        
        else :
            return response()->json([
                'status' => 400,
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
