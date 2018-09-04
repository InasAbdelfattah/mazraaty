<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpParser\Node\Expr\Cast\Object_;
use App\Setting;
use Validator;
use App\FinancialAccount;
use UploadImage;
use App\CompanyWorkDay;
use App\Service;
use App\UserDiscount;
use App\User;
use App\Rate;
use App\OrderService;
use Sms;
use App\OrderAvailableTime;

use App\Notification;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

use Carbon\Carbon;

class OrderController extends Controller
{
    
    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/pays/';
        app()->setlocale(request('lang'));
    }
    
    public function getFinancialReports($centerId){
        $orders = Order::where('company_id', $centerId)->where('status',1)->get();
        $reports = [];
        $profit = 0 ;
        $appRatio = 0;
        $ordersCount = 0;
        $net_app_ratio = 0;
        
        if(count($orders) > 0){

            foreach($orders as $order){

                $profit += $order->net_price;
            };
            
            // foreach($orders as $order){

            //     $appRatio += $order->appRatio;
            // };
            
            $finance = FinancialAccount::where('company_id',$centerId)->first();
            $net_app_ratio = $finance->net_app_ratio ;
            //$appRatio = Setting::getBody('commission') * $profit /100;

            $ordersCount = $orders->count();
        }

        $reports = ['ordersCount' => $ordersCount , 'totlalProfit' => $profit , 'totalAppRatio' => $net_app_ratio ] ;
        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $reports
        ]);
                    
    }
    

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */

    public function getUserOrders(Request $request){

        $user = auth()->user();
        
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'user not found',
                'data' => []
            ]);
        }
        
        $waiting_time = (int)Setting::getBody('waiting_time');
        $end = date("H:i", strtotime('+'.$waiting_time.' minutes'));
        $query = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->select();
        
        if($request->status){
            $status = intval($request->status);
            if($status == 1){
                //$query->where('status', 1);
                $query->whereIn('status', [1, 2, 5,4]);
            }elseif($status == 2){
                //$query->where('status','!=', 1);
                $query->whereIn('status', [0, 3, 6]);
            }
        }else{
            $status = null ;
        }
        
        // if($request->status):
        //     $status = intval($request->status);
        //     $query->where('status', $request->status);
        // endif;
        
        // if ($request->status) {

        //     $query->where('status', $request->status);
        // }

        $orders = $query->select('id','place','order_date','order_time','lat','lng','address','price','min_cost','max_cost','discount_accept','user_id','service_id','company_id as centerId' , 'provider_id' , 'status' , 'refuse_reasons','user_is_finished','created_at','cancel_reason')->get();
        
        $waiting_time = (int)Setting::getBody('waiting_time');
        $end = date("Y-m-d H:i:s", strtotime('+'.$waiting_time.' minutes'));
        
        $orders->map(function ($q) use ($user , $end ,$waiting_time) {
            
            if($user->is_provider == 1):
                $q->username= Company::find($q->centerId) ?Company::find($q->centerId)->{'name:'.app()->getlocale()} : '';
            else:
                $q->username= User::find($q->user_id) ? User::find($q->user_id)->name : '';
            endif;
            
            $q->centerName= Company::find($q->centerId) ?Company::find($q->centerId)->{'name:'.app()->getlocale()} : '';
            
            $q->serviceName= Service::find($q->service_id) ?Service::find($q->service_id)->{'name:'.app()->getlocale()} : '';
            
            if($q->place == 'center'):
                $q->serviceAddress = Company::find($q->centerId) ?Company::find($q->centerId)->address : '';
            else:
                $q->serviceAddress = $q->address;
            endif;
            
            $q->provider_rate = Rate::where('company_id', $q->centerId)->where('order_id', $q->id)->where('rate_from','provider')->first() ? true : false;
            
            $q->user_rate = Rate::where('company_id', $q->centerId)->where('order_id', $q->id)->where('rate_from','user')->first() ? true : false;
            
            $q->waiting_time = $waiting_time;
            // $hours = floor($diff / (60 * 60));
            // $minutes = $diff - $hours * (60 * 60);
            $now = date("Y-m-d H:i:s");
            $end2 =  date("Y-m-d H:i:s", strtotime($q->created_at . '+'.$waiting_time.' minutes'));
            $q->now = $now;
            $q->end = $end2;
            $q->diff = strtotime($end2) - strtotime($now);
            
            if($q->status == 0 && $q->diff <= 0){
                $order = Order::find($q->id);
                $order->status = 4; //
                $order->save();
            }
            
            $q->times = $q->availableTimes;
            $q->current = date("Y-m-d");
            $now = Carbon::now();
            $current_time = $now->toTimeString();
            $q->current_time = $current_time;
            // if($q->status == 3 && $q->order_date > date("Y-m-d") && $q->order_time >= $current_time){
            //     $q->has_ensure = 1;
            // }else{
            //     $q->has_ensure = 0;
            // }
            
            if($q->status == 3 && $q->order_date <= date("Y-m-d")){
                if($q->order_date == date("Y-m-d")){
                    if($q->order_time <= $current_time){
                        $q->has_ensure = 1;
                    }else{
                        $q->has_ensure = 0;
                    }
                }else{
                    $q->has_ensure = 1;
                }
                $q->has_ensure = 1;
            }else{
                $q->has_ensure = 0;
            }
            
            /*status:
                0 => 'new'
                1 => 'finished'
                2 => 'rejected'
                3 => 'accepted'
                4 => 'late'
            */
            
            $finished_order = Rate::where('order_id' , $q->id)->where('rate_from','provider')->first();
            if($finished_order):
                $q->provider_cost = $finished_order->price;
            else:
                $q->provider_cost = null;
            endif;
            
         });
         
        // if($status == 2):
        //     $orders->reject(function ($q){
        //             return $q->diff <= 0;
        //     });
        // endif;
        
        //$companies = $filtered->first();
        // $company = [];
        // if(count($filtered) > 0){
        //     foreach($filtered as $filter){
        //         array_push($company , $filter);
        //     }
        // }

        $ordersCount = $orders->count();

        return response()->json([
            'status' => true,
            //'message' => $status,
            'data' => ['ordersCount' => $ordersCount , 'orders' => $orders ]
        ]);
    }
    
    public function editOrderTime(Request $request){
        $user = auth()->user();
        if(! $user){
            return response()->json([
                'status' => false,
                'message' => 'unavailable',
                'data' => []
            ]);
        }
        
        $order = Order::find($request->orderId);

        if(!$order){
            return response()->json([
                'status' => false,
                'message' => 'order not found',
                'data' => []
            ]);
        }

        if($order->user_id != $user->id){
            return response()->json([
                'status' => false,
                'message' => 'user is not the owner of this order',
                'data' => []
            ]);
        }
        
        $order_time = OrderAvailableTime::find($request->timeId);
        if(!$order_time){
            return response()->json([
                'status' => false,
                'message' => 'time not found',
                'data' => []
            ]);
        }
        
        $order->status = 3;
        $order->order_time = $order_time->time;
        $order->order_date = $order_time->day;
        $order->save();
        
        return response()->json([
            'status' => true,
            //'message' => $status,
            'data' => []
        ]);
        
    }

    public function changeOrderStatus(Request $request){
        
        //return response()->json(['request'=>$request->refuse_type]);
        $provider = auth()->user();
        if(!$provider){
            if($provider->is_provider !=1){
                return response()->json([
                    'status' => false,
                    'message' => 'unavailable',
                    'data' => []
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'user not found',
                'data' => []
            ]);
        }

        $order = Order::find($request->orderId);

        if(!$order){
            return response()->json([
                'status' => false,
                'message' => 'order not found',
                'data' => []
            ]);
        }

        if($order->provider_id != $provider->id){
            return response()->json([
                'status' => false,
                'message' => 'user is not the provider of this service',
                'data' => []
            ]);
        }
        
        // if($request->status != 2 || $request->status != 3){
        //     if(app()->getlocale() == 'ar'):
        //         $msg = 'غير متاح';
        //     else:
        //         $msg = 'unavailable';
        //     endif;
        //     return response()->json([
        //         'status' => false,
        //         'message' => $msg,
        //         'data' => []
        //     ]);
        // }
        $status = 0;
        // if($request->status ==1):
        //     //oderer is accepted
        //     $status = 3;
        // elseif($request->status ==2):
        //     //order is refused
        //     $status = 2;
        // endif;
        
        //status = 1 --> order is finished

        $order->status = $request->status;
        if($request->status == 2){
            
            $order->refuse_type = $request->refuse_type;
            if($request->refuse_type ==0){
                $rules = [
                    'refuse_reason' => 'required|string|min:2',
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $errors = validateRules($validator->errors(), $rules);
                    return response()->json(['status'=>false,'message' => $errors]);
                }
                $order->status =  2 ;
                $order->refuse_reasons = $request->refuse_reason;
                
                if(app()->getlocale() == 'ar'):
                    $title = 'حالة الطلب';
                    $msg = 'تم رفض الطلب';
                else:
                    $title = 'order status';
                    $msg = 'your order has been rejected for the reason :'.$request->refuse_reason;
                endif;
                
                $notif = $this->sendSingleNotification($title , $msg , $order->user_id ,'order',$order->id);
            
            }elseif($request->refuse_type ==1){
                $order->refuse_reasons = '';
                //`order_id`, `day`, `time`
                
                $rules = [
                    'available_times' => 'required',
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $errors = validateRules($validator->errors(), $rules);
                    return response()->json(['status'=>false,'message' => $errors]);
                }
                
                if($request->available_times && count($request->available_times) > 0){
                    
                   // $day = $request->available_times['day'] ;
                   
                    $current = date("Y-m-d");
                    $now = Carbon::now();
                    $current_time = $now->toTimeString();
                    
                    
                    if($request->available_times['day'] <= $current):
                        if($request->lang && $request->lang == 'ar'):
                           $msg = 'يرجى اختيار تاريخ صحيح ';
                        else:
                            $msg = 'date is unavailable';
                        endif;
                        return response()->json(['status'=>false,'message' => $msg]);
                    endif;

                    $day = date('D', strtotime($request->available_times['day']));
                    
                    $workDays = CompanyWorkDay::where('company_id',$order->company_id)->get();
                    
                    $days = $workDays->pluck('day')->toArray();
                    
                        if(!in_array($day, $days)){
                            
                           if($request->lang && $request->lang == 'ar'):
                               $msg = 'هذا اليوم خارج نطاق ايام العمل';
                            else:
                                $msg = 'day out of work days';
                            endif;
                                
                            return response()->json([
                                'status' => false,
                                'message' => $msg,
                                'data' => $day
                            ]);
                        }
                    
                    $time_range = $workDays->where('day',$day)->first();

                    foreach($request->available_times['times'] as $time){
                        
                        
                        //return response()->json(['available_times'=>$time]);
                        if(!( $time >= $time_range->from && $time <= $time_range->to )){
            
                            if($request->lang && $request->lang == 'ar'):
                               $msg = 'الوقت خارج دوام العمل';
                            else:
                                $msg = 'time out of work day time';
                            endif;
                        
                            return response()->json([
                                'status' => false,
                                'message' => $msg,
                            ]);
                        }
                        
                        $available_times = new OrderAvailableTime();
                        $available_times->order_id = $order->id;
                        $available_times->day = $request->available_times['day'];
                        $available_times->time = $time;
                        $available_times->save();
                    }
                }
                
                $order->status =  6 ; //postponed as order time is unavailable
                if(app()->getlocale() == 'ar'):
                    $title = 'حالة الطلب';
                    $msg = 'وقت الطلب غير متاح';
                else:
                    $title = 'order status';
                    $msg = 'order time is not available';
                endif;
                
                $notif = $this->sendSingleNotification($title , $msg , $order->user_id ,'unavailable',$order->id);
                
            }
        }else{
            
            if(app()->getlocale() == 'ar'):
                $title = 'حالة الطلب';
                $msg = 'تم قبول الطلب الخاص بك رقم'." ". $order->id;
            else:
                $title = 'order status';
                $msg = 'your order number '.$order->id.' has been accepted';
            endif;
            
            $notif = $this->sendSingleNotification($title , $msg , $order->user_id ,'order',$order->id);
                
        }

        $order->save();
        
        // if(app()->getlocale() == 'ar'):
        //         $msg = 'تم قبول الطلب';
        // else:
        //     $msg = 'your order has been accepted';
        // endif;
        
        // if(app()->getlocale() == 'ar'):
        //     $msg = 'تم قبول الطلب';
        //         $title = 'حالة الطلب';
        // else:
        //     $title = 'order status';
        //     $msg = 'your order has been accepted';
        // endif;
            
        
        $user = User::find($order->user_id);
        if($user){
            Sms::sendActivationCode($msg , $user->phone);
        }
       
        //$this->sendSingleNotification($title , $msg , $user_id ,$notif_type);
        
        
        return response()->json([
            'status' => true,
            'message' => 'done',
            'data' => ['order' => $order ,'notif'=>$notif]
        ]);

    }

    public function providerNewOrders(){

        $provider = auth()->user();
        $center = Company::where('user_id',$provider->id)->first();

        if(!$center){
            return response()->json([
                'status' => false,
                'message' => 'center not found',
                'data' => []
            ]);
        }

        $orders = Order::where('company_id', $center->id)->whereIn('status', [0, 3, 6])->select('id','place','order_date','order_time','lat','lng','address','price','min_cost','max_cost','discount_accept','discount','user_id','service_id','company_id as centerId' , 'provider_id' , 'status','created_at','cancel_reason')->orderBy('id','desc')->get();
        
        $waiting_time = (int)Setting::getBody('waiting_time');
        
        $end = date("Y-m-d H:i:s", strtotime('+'.$waiting_time.' minutes'));
        
        
        $orders->map(function ($q) use ($provider , $end , $waiting_time) {
            
            // if($provider->is_provider == 1):
            //     $q->username= Company::find($q->centerId) ?Company::find($q->centerId)->{'name:'.app()->getlocale()} : '';
            // else:
            //     $q->username= User::find($q->user_id) ? User::find($q->user_id)->name : '';
            // endif;
            $q->username= User::find($q->user_id) ? User::find($q->user_id)->name : '';
            
            $q->centerName= Company::find($q->centerId) ?Company::find($q->centerId)->{'name:'.app()->getlocale()} : '';
            
            $q->serviceName= Service::find($q->service_id) ?Service::find($q->service_id)->{'name:'.app()->getlocale()} : '';
            
            if($q->place == 'center'):
                $q->serviceAddress = Company::find($q->centerId) ?Company::find($q->centerId)->address : '';
            else:
                $q->serviceAddress = $q->address;
            endif;
            
            $q->provider_rate = Rate::where('company_id', $q->centerId)->where('order_id', $q->id)->where('rate_from','provider')->first() ? true : false;
            
            $q->user_rate = Rate::where('company_id', $q->centerId)->where('order_id', $q->id)->where('rate_from','user')->first() ? true : false;
            
            
            // if($q->status == 0 && $q->diff <= 0){
            //     $order = Order::find($q->id);
            //     $order->status = 4; //
            //     $order->save();
            // }
            
            ///
                $q->waiting_time = $waiting_time;
            // $hours = floor($diff / (60 * 60));
            // $minutes = $diff - $hours * (60 * 60);
            $now = date("Y-m-d H:i:s");
            $end2 =  date("Y-m-d H:i:s", strtotime($q->created_at . '+'.$waiting_time.' minutes'));
            $q->now = $now;
            $q->end = $end2;
            $q->diff = strtotime($end2) - strtotime($now);
            
            if($q->status == 0 && $q->diff <= 0){
                $order = Order::find($q->id);
                $order->status = 4; //
                $order->save();
            }
            
            ///
            
            $q->current = date("Y-m-d");
            $now = Carbon::now();
            $current_time = $now->toTimeString();
            $q->current_time = $current_time;
            if($q->status == 3 && $q->order_date <= date("Y-m-d")){
                if($q->order_date == date("Y-m-d")){
                    if($q->order_time <= $current_time){
                        $q->has_ensure = 1;
                    }else{
                        $q->has_ensure = 0;
                    }
                }else{
                    $q->has_ensure = 1;
                }
                $q->has_ensure = 1;
            }else{
                $q->has_ensure = 0;
            }
                
            
         });

        $ordersCount = $orders->count();

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => ['ordersCount' => $ordersCount , 'orders' => $orders ]
        ]);
    }

    public function providerFinishedOrders(){

        $provider = auth()->user();
        $center = Company::where('user_id',$provider->id)->first();

        if(!$center){
            return response()->json([
                'status' => false,
                'message' => 'center not found',
                'data' => []
            ]);
        }

        $totalOrders = Order::where('company_id', $center->id)->whereIn('status', [1, 2, 4 , 5])->orderBy('id','desc')->get();
        
        $waiting_time = (int)Setting::getBody('waiting_time');
        $end = date("Y-m-d H:i:s", strtotime('+'.$waiting_time.' minutes'));
        
        $totalOrders->map(function ($q) use ($provider , $end ,$waiting_time) {
            
            // if($provider->is_provider == 1):
            //     $q->username= Company::find($q->centerId) ?Company::find($q->centerId)->{'name:'.app()->getlocale()} : '';
            // else:
            //     $q->username= User::find($q->user_id) ? User::find($q->user_id)->name : '';
            // endif;
            
             $q->username= User::find($q->user_id) ? User::find($q->user_id)->name : '';
            
            $q->centerName= Company::find($q->centerId) ?Company::find($q->centerId)->{'name:'.app()->getlocale()} : '';
            
            $q->serviceName= Service::find($q->service_id) ?Service::find($q->service_id)->{'name:'.app()->getlocale()} : '';
            
            if($q->place == 'center'):
                $q->serviceAddress = Company::find($q->centerId) ?Company::find($q->centerId)->address : '';
            else:
                $q->serviceAddress = $q->address;
            endif;
            
            $q->provider_rate = Rate::where('company_id', $q->centerId)->where('order_id', $q->id)->where('rate_from','provider')->first() ? true : false;
            
            $q->user_rate = Rate::where('company_id', $q->centerId)->where('order_id', $q->id)->where('rate_from','user')->first() ? true : false;
            
            // $hours = floor($diff / (60 * 60));
            // $minutes = $diff - $hours * (60 * 60);
            
             $q->waiting_time = strtotime($end) - strtotime($q->created_at);
             
             $q->waiting_time = $waiting_time;
            // $hours = floor($diff / (60 * 60));
            // $minutes = $diff - $hours * (60 * 60);
            $now = date("Y-m-d H:i:s");
            $end2 =  date("Y-m-d H:i:s", strtotime($q->created_at . '+'.$waiting_time.' minutes'));
            $q->now = $now;
            $q->end = $end2;
            $q->diff = strtotime($end2) - strtotime($now);
            
            if($q->status == 0 && $q->diff <= 0){
                $order = Order::find($q->id);
                $order->status = 4; //
                $order->save();
            }
            
            $q->current = date("Y-m-d");
            $now = Carbon::now();
            $current_time = $now->toTimeString();
            $q->current_time = $current_time;
            if($q->status == 3 && $q->order_date > date("Y-m-d") && $q->order_time >= $current_time){
                $q->has_ensure = 1;
            }else{
                $q->has_ensure = 0;
            }

         });
        
        
        $totalProfit = 0 ;
        
        if(count($totalOrders) > 0){
        
            foreach($totalOrders as $order){

                $totalProfit += $order->price;
            };
        
        }
        
        $totalAppRatio = Setting::getBody('commission') * $totalProfit /100;
        
        $orders = Order::where('company_id', $center->id)->where('status',3)->where('is_considered',0)->get();

        $profit = 0 ;
        
        if(count($orders) > 0){

            foreach($orders as $order){

                $profit += $order->price;
            };
        }

        $appRatio = Setting::getBody('commission') * $profit /100;

        $ordersCount = $orders->count();

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => ['orders'=>$totalOrders ,'ordersCount' => $ordersCount , 'totlalProfit' => $profit , 'totalAppRatio' => $totalAppRatio ,'appRatio' => $appRatio]
        ]);
    }

    public function payAppRatio(Request $request){
       // return response()->json($request);
        $provider = auth()->user();
        $center = Company::where('user_id',$provider->id)->first();

        if(!$center){
            return response()->json([
                'status' => false,
                'message' => 'center not found',
                'data' => []
            ]);
        }
//$company->image = UploadImage::uploadImage($request,'image', $this->public_path);
        $rules = [
            'transfered_money' => 'required|numeric',
           // 'pay_photo' => 'required'
            //'ordersCount' => 'required|integer|min:1',
            //'appRatio' => 'required',
            'pay_photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);

            return response()->json(['status'=>false,'data' => $error_arr]);
        }

        $orders = Order::where('company_id',$center->id)->where('status',1)->get();

        $appRatio = 0;
        $orders_count = 0;
        if(count($orders) > 0){
            foreach($orders as $order){
    
                $appRatio += $order->app_ratio;
            }
            $orders_count = count($orders) ;
        }
        // if(count($orders) > 0){

        //     foreach($orders as $order){

        //         $order->is_considered = 1 ;

        //         $order->save();

        //     }
        // }
        $transfered = (int)$request->transfered_money;
        $model = FinancialAccount::where('company_id',$center->id)->first();
        if(!$model){
            $newModel = new FinancialAccount();
            $newModel->company_id = $center->id;
            $newModel->orders_count = $orders_count ;
            $newModel->net_app_ratio = $appRatio;
            $newModel->transfered_money = $transfered;
            $newModel->last_transfered_money = $transfered;
            $newModel->pay_status = 1;
            if ($request->hasFile('pay_photo')):
                $newModel->pay_doc = UploadImage::uploadImage($request,'pay_photo', $this->public_path);
            else:
                $newModel->pay_doc = '';
            endif; 

            $newModel->paid = 0;
            $newModel->remain = 0;
            $newModel->is_confirmed = 0;
            $newModel->pay_status = 1;
            $newModel->save();

            return response()->json([
                'status' => true,
                'message' => 'done',
                'data' => []
            ]);
        }else{
            //$model->orders_count = $request->ordersCount;
            //$model->net_app_ratio = $request->appRatio;
            $model->transfered_money = (int)$model->transfered_money + $transfered ;
            $model->last_transfered_money = $transfered;
            $model->pay_status = 1;
    
            if ($request->hasFile('pay_photo')):
                $model->pay_doc = UploadImage::uploadImage($request,'pay_photo', $this->public_path);
            endif; 

        $model->is_confirmed = 0;
        $model->pay_status = 1;
        $model->save();
        }

        

        return response()->json([
            'status' => true,
            'message' => 'done',
            'data' => []
        ]);
    }

    public function saveOrder(Request $request){
        
        $user = auth()->user();
        
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'user not found',
                'data' => []
            ]);
        }

        $rules = [
            'place' => 'required',
            'centerId' => 'required',
            //'serviceId' => 'required',
            'services' => 'required',
            'order_date' => 'date_format:"Y-m-d"|required|after_or_equal:today',
            'order_time' => 'date_format:"H:i:s"|required',
        ];

        $discounts = UserDiscount::where('user_id',$user->id)->where('to_date','>=',date('Y-m-d'))->where('is_used',0)->get();

        if($discounts->count() > 0){
            $rules['discount_accept'] = 'required';
        }

        if($request->discount_accept == 1){
            $rules['discountId'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);

            return response()->json(['status'=>false,'data' => $error_arr]);
        }
        
        $orderTime = date('H:i:s', strtotime($request->order_time));
        $mytime = Carbon::now();

        if($request->order_date == $mytime->toDateString()){
            if($orderTime <= $mytime->toTimeString()){
                if($request->lang && $request->lang == 'ar'):
                   $msg = 'غير متاح الحجز فى وقت سابق';
                else:
                    $msg = 'unavailable to request order in previous time';
                endif;
                return response()->json([
                    'status' => false,
                    'message' => $msg,
                    'data' => []
                ]);
            }
        }

        $company = Company::find($request->centerId);
        //$service = Service::find($request->serviceId);
        if(!$company){
            return response()->json([
                'status' => false,
                'message' => 'center not found',
                'data' => []
            ]);
        }

        // if(!$service){
        //     if($request->lang && $request->lang == 'ar'):
        //       $msg = 'الخدمة غير متاحة';
        //     else:
        //         $msg = 'service not found';
        //     endif;
        //     return response()->json([
        //         'status' => false,
        //         'message' => $msg,
        //         'data' => []
        //     ]);
        // }

        $workDays = CompanyWorkDay::where('company_id',$company->id)->get();
        $days = $workDays->pluck('day')->toArray();
        $day = date('D', strtotime($request->order_date));

        if(!in_array($day, $days)){
            
           if($request->lang && $request->lang == 'ar'):
               $msg = 'هذا اليوم خارج نطاق ايام العمل';
            else:
                $msg = 'day out of work days';
            endif;
                
            return response()->json([
                'status' => false,
                'message' => $msg,
                'data' => $day
            ]);
        }

        $time_range = $workDays->where('day',$day)->first();

        if(!( $request->order_time >= $time_range->from && $request->order_time <= $time_range->to )){
            
            if($request->lang && $request->lang == 'ar'):
               $msg = 'الوقت خارج دوام العمل';
            else:
                $msg = 'time out of work day time';
            endif;

            return response()->json([
                'status' => false,
                'message' => $msg,
                'data' => $day
            ]);
        }

        $newModel = new Order();
        $newModel->user_id = $user->id;
        $newModel->gender = 'male';
        $newModel->place = $request->place;
        if($request->place == 'home'){

            $rules = [
                'lat' => 'required',
                'lng' => 'required',
                'address' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errors = validateRules($validator->errors(), $rules);
                return response()->json(['status'=>false,'message' => $errors]);
            }

            $newModel->lat = $request->lat ;
            $newModel->lng = $request->lng ;
            $newModel->address = $request->address;
        }else{

            $newModel->lat = $request->lat ? $request->lat : '' ;
            $newModel->lng = $newModel->lng ? $newModel->lng : '' ;
            $newModel->address = $newModel->address ? $newModel->address : '';
        }
        $newModel->notes = '';
        $newModel->order_date = $request->order_date;
        $newModel->order_time = $request->order_time;
        //$newModel->price = $service->price;
        //$newModel->net_price = $service->price;
        $newModel->net_price = 0;
        $newModel->discount_accept = $request->discount_accept;
        $newModel->company_id = $request->centerId;
        //$newModel->service_id = $request->serviceId;
        $newModel->service_id = 0;
        $newModel->provider_id = $company->user_id;
        $newModel->status = 0;
        $newModel->user_is_finished = 0;
        $newModel->is_considered = 0;
        $newModel->refuse_reasons = '';
        $message = 'done';

        $newModel->save();
        $net_price = 0;
        $min_cost = 0;
        $max_cost = 0;
        $service_type = 0;
        
        if($request->has('services')){
            $services = explode(',',$request->services);
            //$services = json_decode($request->services);
            //dd($days);
            
            if(count($services) > 0){
                foreach($services as $service){
                    $model = new OrderService;
                    $model->order_id = $newModel->id;
                    $model->service_id = $service;
                    
                    $aservice = Service::find($service);
                    
                    if($aservice):
                        $net_price += $aservice->price;
                        $min_cost += $aservice->price;
                        $max_cost += $aservice->price;
                        
                        $min_cost += $aservice->min_cost;
                        $max_cost += $aservice->max_cost;
                        

                        $model->price = $aservice->price;
                        $model->min_cost = $aservice->min_cost;
                        $model->max_cost = $aservice->max_cost;
                        $service_type = $aservice->serviceType_id;
                    endif;
                    
                    
                    $model->save();
                }
            }
        }
        
        $newModel->net_price = $net_price ;
        $newModel->min_cost = $min_cost ;
        $newModel->max_cost = $max_cost ;
        
                if($request->discount_accept == 1){
            //send order with user discount to provider
            $discount = UserDiscount::where('id',$request->discountId)->where('from_date','>=',date('Y-m-d'))->where('to_date','>=',date('Y-m-d'))->first();
//dd($discount);
            if($discount){
                $diff = $discount->max_orders - $discount->is_used ;
                if($diff > 0){
                    $discount->is_used = $discount->is_used + 1 ;
                    $discount->save();
                    
                    if($max_cost):
                        $newModel->discount = ($discount->discount * $min_cost) / 100 ;
                    else:
                        $newModel->discount = ($discount->discount * $net_price) / 100 ;
                    endif;
                    
                }else{
                    $newModel->discount = 0;

                    if(app()->getlocale() == 'ar'):
                        $message = 'تم استهلاك الحد الاقصى المسموح استخدامه';
                    else:
                        $message = 'you have reached the max number of orders you could use the code in';
                    endif;
                    
                    return response()->json([
                        'status' => true,
                        'message' => $message
                    ]);
                
                }
            }else{
                
                $newModel->discount = 0;
                if(app()->getlocale() == 'ar'):
                    $message = 'كود غير صحيح';
                else:
                    $message = 'incorrect code';
                endif;
                
                return response()->json([
                    'status' => true,
                    'message' => $message
                ]);
            }
            
            

        }else{
            //send order only to provider
            $newModel->discount = 0;
        }
        
        $newModel->price = $newModel->net_price - $newModel->discount;
        if($newModel->max_cost != null):
            $app_ratio = Setting::getBody('commission') * $newModel->min_cost /100;
        else:
            $app_ratio = Setting::getBody('commission') * $newModel->net_price /100;
        endif;
        $newModel->app_ratio = $app_ratio - $newModel->discount;
        $newModel->service_id = $service_type;
        $newModel->save();
        
        if(app()->getlocale() == 'ar'):
            
                $title = $user->name;
                $msg = 'تم اضافة طلب جديد';
                
        else:
            $title = $user->name ;
            $msg = 'new order has been added';
        endif;
        
        $noti =$this->sendSingleNotification($title , $msg , $company->user_id ,'new_order' ,$newModel->id);
            
        
        //$usr = User::find($newModel->user_id);
        // if($usr){
        //     Sms::sendActivationCode($msg , $usr->phone);
        // }
        
      

        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => ['notif'=>$noti]
        ]);
    }
    
    public function deleteOrder(Request $request){
        
        $user = auth()->user();
        if(!$user){
            return response()->json([
                'status' => false ,
                'message' => 'userNotFound' ,
                'data' => []
            ]);
        }
        
        $order = Order::where('id',$request->orderId)->first();
        
        if(!$order){
            
            return response()->json([
                'status' => false ,
                'message' => 'orderNotFound' ,
                'data' => []
            ]);
        }
        
        if($order->user_id != $user->id){
            if(app()->getlocale() == 'ar'):
                $msg = 'عفوا لا يمكنك حذف هذا الحجز';
            else:
                $msg = 'you cannot delete this order';
            endif;
            
            
        
            return response()->json([
                'status' => false ,
                'message' => $msg ,
                'data' => []
            ]);
        }
        
        $order_rate = Rate::where('order_id',$order->id)->first();
        if($order_rate){
            if(app()->getlocale() == 'ar'):
                $msg = 'لا يمكنك حذف الطلب حيث تم تأكيده';
            else:
                $msg = 'you cannot delete this order as it has been confirmed';
            endif;
            
            return response()->json([
                'status' => false ,
                'message' => 'order has been confirmed' ,
                'data' => []
            ]);
        }
        
        //$order->delete();
        $order->status = 5;
        $order->cancel_reason = $request->reason ;
        $order->save();
        
        if(app()->getlocale() == 'ar'):
            $title = 'تم الغاء طلب';
            $message = 'تم الغاء الطلب رقم '.$order->id;
        else:
            $title = 'order cancelaton';
            $message = 'the order no.'.$order->id . 'has been canceled';
        endif;

        $this->sendSingleNotification($title , $message , $order->provider_id ,'order',$order->id);
        
        return response()->json([
            'status' => true ,
            'message' => 'order has been deleted' ,
            'data' => []
        ]);
            
    }
    
    public function editOrder(Request $request){
        
        $user = auth()->user();
        if(!$user){
            return response()->json([
                'status' => false ,
                'message' => 'userNotFound' ,
                'data' => []
            ]);
        }
        
        // $order = Order::where('id',$request->orderId)->where('user_id',$user->id)->first();
        
        $order = Order::where('id',$request->orderId)->first();
        
        if(!$order){
            
            return response()->json([
                'status' => false ,
                'message' => 'orderNotFound' ,
                'data' => []
            ]);
        }
        
        
        if($order->user_id != $user->id){
            if(app()->getlocale() == 'ar'):
                $msg = 'عفوا لا يمكنك تعديل هذا الحجز';
            else:
                $msg = 'you cannot edit this order';
            endif;
        
        //$noti =$this->sendSingleNotification($title , $msg , $company->user_id ,'new_order');
            return response()->json([
                'status' => false ,
                'message' => $msg ,
                'data' => []
            ]);
        }
        
        $order_rate = Rate::where('order_id',$order->id)->first();
        if($order_rate){
            return response()->json([
                'status' => false ,
                'message' => 'order has been confirmed' ,
                'data' => []
            ]);
        }
        
        //$order->delete();
        
        $company = Company::find($order->company_id);

        if(!$company){
            return response()->json([
                'status' => false,
                'message' => 'center not found',
                'data' => []
            ]);
        }
        
        
        if($request->order_date && $request->order_time){
            
            $workDays = CompanyWorkDay::where('company_id',$order->company_id)->get();
        
            if(!$workDays){
                return response()->json([
                    'status' => false,
                    'message' => 'no work days were assigned to center',
                    'data' => []
                ]);
            }
        }

        if($request->order_date){
            
            
            $days = $workDays->pluck('day')->toArray();
            $day = date('D', strtotime($request->order_date));
            
            if(!in_array($day, $days)){
                
               if($request->lang && $request->lang == 'ar'):
                   $msg = 'هذا اليوم خارج نطاق ايام العمل';
                else:
                    $msg = 'day out of work days';
                endif;
                    
                return response()->json([
                    'status' => false,
                    'message' => $msg,
                    'data' => $day
                ]);
            }
            
            $order->order_date = $request->order_date;
        }
        
        if($request->order_time){

            $time_range = $workDays->where('day',$day)->first();
    
            if(!( $request->order_time >= $time_range->from && $request->order_time <= $time_range->to )){
                
                if($request->lang && $request->lang == 'ar'):
                   $msg = 'الوقت خارج دوام العمل';
                else:
                    $msg = 'time out of work day time';
                endif;
    
                return response()->json([
                    'status' => false,
                    'message' => $msg,
                    'data' => $day
                ]);
            }
            $order->order_time = $request->order_time;
        
        }
        
        $order->place = $request->place ? $request->place : $order->place;
        $order->lat = $request->lat ? $request->lat : $order->lat ;
        $order->lng = $request->lng ? $request->lng : $order->lng ;
        $order->address = $request->address ? $request->address : $order->address;
        
        $order->save();
        
        
        // if(app()->getlocale() == 'ar'):
            
        //         $title = $user->name;
        //         $msg = 'تم تعديل طلب';
                
        // else:
        //     $title = $user->name ;
        //     $msg = 'order has been edited';
        // endif;
        
        // $noti =$this->sendSingleNotification($title , $msg , $company->user_id ,'edit_order');
        
        if(app()->getlocale() == 'ar'):
            $msg = 'تم تعديل الطلب';
                
        else:
            $msg = 'order has been edited';
        endif;
        
        return response()->json([
            'status' => true ,
            'message' => $msg ,
            'data' => []
        ]);
            
    }
    
    public function getOrderDetails(Request $request){
        
        $order = Order::where('id',$request->orderId)->first();
        
        if(!$order){
            
            return response()->json([
                'status' => false ,
                'message' => 'orderNotFound' ,
                'data' => []
            ]);
        }
        $user = User::where('id',$order->user_id)->select('id','name')->first();
        if($user):
            $order->user = $user->name;
        endif;
        
        $now = Carbon::now();
        $current_time = $now->toTimeString();
        
        $center = Company::where('id',$order->company_id)->first();
        if($center):
            $order->center_name = $center->{'name:'.app()->getlocale()};
            $order->provider_rate = Rate::where('company_id', $center->id)->where('order_id', $order->id)->where('rate_from','provider')->first() ? true : false;
            $order->user_rate = Rate::where('company_id', $center->id)->where('order_id', $order->id)->where('rate_from','user')->first() ? true : false;
        endif;
                

        $order->orderServices = OrderService::where('order_id',$request->orderId)->get();
        $order->orderServices->map(function ($q) {
            $service = Service::find($q->service_id);
            if($service):
                $q->service_name = $service->{'name:'.app()->getlocale()};
                //$q->description = $q->{'description:'.app()->getlocale()};
                //$q->image= $request->root() . '/' . $this->public_service_path . $q->photo ;
            endif;

        });
        
        $waiting_time = (int)Setting::getBody('waiting_time');
        $now = date("Y-m-d H:i:s");
        $end2 =  date("Y-m-d H:i:s", strtotime($order->created_at . '+'.$waiting_time.' minutes'));
        
        $order->diff = strtotime($end2) - strtotime($now);
        
        if($order->status == 3 && $order->order_date <= date("Y-m-d")){
            if($order->order_date == date("Y-m-d")){
                if($order->order_time <= $current_time){
                    $order->has_ensure = 1;
                }else{
                    $order->has_ensure = 0;
                }
            }else{
                $order->has_ensure = 1;
            }
            $order->has_ensure = 1;
        }else{
            $order->has_ensure = 0;
        }
        
        $finished_order = Rate::where('order_id' , $order->id)->where('rate_from','provider')->first();

        if($finished_order):
            $order->provider_cost = $finished_order->price;
        else:
            $order->provider_cost = null;
        endif;
        
    
        return response()->json([
                'status' => true ,
                'message' => '' ,
                'data' => $order
            ]);
    }


    public function checkUserDiscounts(){
        $user = auth()->user();
        if(!$user){
            return response()->json([
                'status' => false ,
                'message' => 'user not found' ,
                'data' => []
            ]);
        }

        $discounts = UserDiscount::where('user_id',$user->id)->where('to_date','>=',date('Y-m-d'))->where('is_used',0)->get();

        if($discounts->count() > 0){
            return response()->json([
                'status' => true ,
                'message' => '' ,
                'data' => $discounts
            ]);
        }else{
            return response()->json([
                'status' => false ,
                'message' => 'no discounts' ,
                'data' => []
            ]);
        }

    }
    
    private function sendSingleNotification($title , $msg , $user_id ,$notif_type ,$target){

        $device = \App\Device::where('user_id',$user_id)->orderBy('id','Desc')->first();
        
        //$device = \App\Device::where('user_id',$user_id)->pluck('device')->toArray();
        
        if($device):
            $token = $device->device;
        else:
            $token = '';
        endif;

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($msg)
            ->setSound('default');
        $notificationBuilder->setClickAction("FCM_PLUGIN_ACTIVITY");
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['message' => $msg , 'title'=>$title ,'type' =>$notif_type]);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        $notif = new Notification();
        $notif->msg = $msg;
        $notif->title = $title;
        $notif->image = '';
        $notif->to_user = $user_id;
        $notif->target_id = $target;
        $notif->type = 'single';
        $notif->notif_type = $notif_type;
        $notif->save();
        
        if($token != ''){
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
            $downstreamResponse->numberModification();
            //return Array - you must remove all this tokens in your database
            $downstreamResponse->tokensToDelete();
            //return Array (key : oldToken, value : new token - you must change the token in your database )
            $downstreamResponse->tokensToModify();
            //return Array - you should try to resend the message to the tokens in the array
            $downstreamResponse->tokensToRetry();
            // return Array (key:token, value:errror) - in production you should remove from your database the tokens
            
            return $token;
        }
        
        return false;
    }


}
