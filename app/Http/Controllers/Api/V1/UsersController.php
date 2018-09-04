<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Validator;
use UploadImage;
use Sms;
use App\Company;
use App\CompanyWorkDay;
use App\City;
use App\Device;

class UsersController extends Controller
{

    public $public_path;
    public $public_path_user;
    public $public_path_docs ;

    public function __construct()
    {
        app()->setlocale(request('lang'));
        $this->public_path = 'files/companies/';
        $this->public_path_user = 'files/users/';
        $this->public_path_docs = 'files/docs/';
        
        
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {

        $user = User::with('company.city')->whereApiToken($request->api_token)->first();
        $user->company->name_en = $user->company->{'name:en'};
        $user->company->name_ar = $user->company->{'name:ar'};
        $user->company->description_en = $user->company->{'description:en'};
        $user->company->description_ar = $user->company->{'description:ar'};
        $user->company->city->name_ar = $user->company->city->{'name:ar'};
        $user->company->city->name_en = $user->company->city->{'name:en'};
        return response()->json([
            'status' => true,
            'data' => $user
        ]);

    }


    public function profileUpdate(Request $request)
    {
        $user = auth()->user();
        //$user = User::whereApiToken($request->api_token)->first();

        // Get Input
        // $postData = $this->postData($request);

        // // Declare Validation Rules.
        // $valRules = $this->valRules($user->id);

        // // Declare Validation Messages
        // $valMessages = $this->valMessages();

        // // Validate Input
        // $valResult = Validator::make($postData, $valRules, $valMessages);

        // Check Validate
       // if ($valResult->passes()) {

            if($request->has('name') && $request->name != ''):
                $user->name = $request->name;
            endif;

            if($request->has('email') && $request->email != ''):
                $user->email = $request->user_email;
            endif;

            if($request->has('phone') && $request->phone != ''):
                $user->phone = $request->user_phone;
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
            
            $user->user_city = City::where('id',$user->city)->first();

            if($user->user_city):
                $user->user_city->name = $user->user_city->{'name:'.app()->getlocale()};
            endif;
            
            if($user->image != ''){
                //$photo =  url('files/users/' . $user->image);
                $user->photo= $request->root() . '/' . $this->public_path_user . $user->image ;
            }

            if ( $user->is_provider == 1) {
                $company = Company::where('user_id',$user->id)->first();
               if($company){
                   
                   if($request->has('name') && $request->name != ''){
                       $company->{'name:'.app()->getlocale()} = $request->name;
                   }elseif($request->has('username') && $request->username != ''){
                       $company->{'name:'.app()->getlocale()} = $request->username;
                   }
                   
                   if($request->has('description') && $request->description != ''){
                       $company->{'description:'.app()->getlocale()} = $request->description;
                   }

                    // if($request->has('name_ar') && $request->name_ar != ''):
                    //     $company->{'name:ar'} = $request->name_ar;
                    //     $company->nameAr = $request->name_ar;
                    // endif;
    
                    // if($request->has('name_en') && $request->name_en != ''):
                    //     $company->{'name:en'} = $request->name_en;
                    // endif;
    
                    // if($request->has('description_ar') && $request->description_ar != ''):
                    //     $company->{'description:ar'} = $request->description_ar;
                    // endif;
    
                    // if($request->has('description_en') && $request->description_en != ''):
                    //     $company->{'description:en'} = $request->description_en;
                    // endif;
    
                    if($request->has('city') && $request->city != ''):
                        $company->city_id = $request->city;
                    endif;
                    
                    if($request->has('district') && $request->district != ''):
                        $company->district_id = $request->district;
                    endif;
    
                    if($request->has('providerType') && $request->providerType != ''):
                        $company->type = $request->providerType;
                    endif;
    
                    if ($request->hasFile('document_photo')):
                    //if ($request->has('document_photo')):
                        $company->document_photo = uploadImage($request, 'document_photo', $this->public_path_docs, 1280, 583);
                        //$company->document_photo = save64Img($request->document_photo , $this->public_path_docs);
                    endif;
    
                    if($request->has('address') && $request->address != ''):
                        $company->address = $request->address;
                    endif;
                    
                    if($request->has('lat') && $request->lat != ''):
                        $company->lat = $request->lat;
                    endif;
    
                    if($request->has('lng') && $request->lng != ''):
                        $company->lng = $request->lng;
                    endif;
    
                    if($request->has('category') && $request->category != ''):
                        $company->category_id = $request->category;
                    endif;
    
                    if ($request->hasFile('image')):
                        $company->image = UploadImage::uploadImage($request,'image', $this->public_path);
                    elseif($request->hasFile('userImage')):
                        $company->image = UploadImage::uploadImage($request,'userImage', $this->public_path);
                    endif;
                    if($request->has('workDays')){
                        $days = json_decode($request->workDays);
                        //dd($days);
                        if(count($days) > 0){
                            $old_days = CompanyWorkDay::where('company_id',$company->id)->get();
                                if(count($old_days) > 0){
                                    foreach($old_days as $old){
                                        $old->delete();
                                    }
                                }
                            foreach($days as $day){
                                $model = new CompanyWorkDay;
                                $model->company_id = $company->id;
                                $model->day = $day->day;
                                $model->from = $day->from;
                                $model->to = $day->to;
                                $model->save();
                            }
                        }
                    }
    
    }
                    if ($company->save()) {
                        //$user->center = $user->company()->with('city')->first();
                        $user->center = $user->company()->with('city')->first();
                        //$user->center->cityy = City::where('id',$company->city)->first();
                        $user->center->name = $company->{'name:'.app()->getlocale()};
                        
                        $user->center->description = $company->{'description:'.app()->getlocale()};
                        
                        
                        // $user->company->name_en = $company->{'name:en'};
                        // $user->company->name_ar = $company->{'name:ar'};
                        // $user->company->description_en = $company->{'description:en'};
                        // $user->company->description_ar = $company->{'description:ar'};
                        
                        if($company->document_photo != ''){
                            $user->center->doc_photo= $request->root() . '/' . $this->public_path_docs . $company->document_photo ;
                        }
                        
                        $locale = app()->getlocale() ;
                        $workDays = CompanyWorkDay::where('company_id', $company->id)
                        ->select('id','day','from','to','company_id as centerId')->get();

                        if($workDays->count() > 0){
                            if($locale == 'ar'){
                                $workDays->map(function ($q) {
                                    $q->name = day($q->day);
                                });
                            }else{
                                $workDays->map(function ($q) {
                                    $q->name = dayEn($q->day);
                                });
                            }
                        }
                        
                        $user->workDays = $workDays ;
                        
                        return response()->json([
                            'status' => true,
                            'data' => $user,
                            'code' => $user->action_code
                        ]);
                    
                
               }
            } else {
                
                return response()->json([
                    'status' => true,
                    'data' => $user,
                    'code' => $user->action_code
                ]);
            }


        // } else {
        //     $valErrors = $valResult->messages();
        //     return response()->json([
        //         'status' => true,
        //         'data' => $valErrors,

        //     ], 400);
        // }

    }

    public function changePassword(Request $request)
    {

        $user = User::whereApiToken($request->api_token)->first();
        
        if($request->lang):
            \App::setLocale($request->lang);
        endif;
        
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'newpassword' => 'required',
            'confirm_newpassword' => 'required|same:newpassword',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
            ]);
        }
        $hashedPassword = $user->password;
        if (Hash::check($request->old_password, $hashedPassword)) {
            //Change the password
            $user->fill([
                'password' => Hash::make($request->newpassword)
            ])->save();
            if($request->lang && $request->lang == 'en'):
                $msg = 'password  has been edited successfuuly';
            else:
                $msg = 'لقد تم تعديل كلمة المرور بنجاح';
            endif;
            return response()->json([
                'status' => true,
                'message' => $msg ,
                'data' => null
            ]);
        } else {
            
            if($request->lang && $request->lang == 'en'):
                $msg = 'old password is false' ;
            else:
                $msg = 'كلمة المرور القديمة غير صحيحة';
            endif;
            
            return response()->json([
                'status' => false,
                'message' => $msg ,
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
