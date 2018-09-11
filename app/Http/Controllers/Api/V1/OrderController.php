<?php

namespace App\Http\Controllers\Api\V1;

use App\Basket;
use App\Item;
use App\Order;
use App\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpParser\Node\Expr\Cast\Object_;
use App\Setting;
use Validator;
use App\workDay;
use App\Coupon;
use App\User;
use App\Product;
use App\UserAddress;

use App\Notification;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

use Carbon\Carbon;

class OrderController extends Controller
{
    
    public function getUserRecentOrder(Request $request){

        $user = auth()->user();
        
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'user not found',
                'data' => []
            ]);
        }
        
        $order = Order::where('user_id',$user->id)->orderBy('id','Desc')->select('id','user_id', 'basket_id', 'coupon_id', 'total_price', 'discount', 'order_date', 'order_time', 'address_id', 'status', 'user_deliverd_time')->first();

        if($order->coupon_id != null):
            $coupon = Coupon::find($order->coupon_id);
            $order->couponCode= $coupon ? $coupon->code : '';
        endif;
        $address = UserAddress::find($order->address_id);
        $order->address= $address ? $address->address : '';    

        $items = Item::where('basket_id',$order->basket_id)->select('id','itemable_id','amount','itemable_type')->get();
        $items->map(function ($q) {
            $product = Product::find($q->itemable_id);
            $offer = Offer::find($q->itemable_id);
            if($q->itemable_type == 'App\Product'):
                $q->product_name = $product->name;
                $q->product_price = $product->price;
                $q->product_image= Request()->root() . '/files/products/' . $product->image ;
                //$q->offer_name = null;
                $q->offer_price = null;
            else:
                $offer_product = Product::find($offer->product_id);
                //$q->offer_name = $offer->name;
                $q->offer_price = $offer->price;
                $q->product_name = $offer_product->name;
                $q->product_price = $offer_product->price;
                $q->product_image= Request()->root() . '/files/products/' . $offer_product->image ;

            endif;
        });

        $order->items = $items;
        
        return response()->json([
            'status' => true,
            //'message' => $status,
            'data' => $order
        ]);
    }

    public function getUserOrders(Request $request){

        $user = auth()->user();
        
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'user not found',
                'data' => []
            ]);
        }

        $pageSize = $request->get('pageSize', 15);
        $skipCount = $request->get('skipCount', 0);
        
        $query = Order::where('user_id',$user->id)->orderBy('id','Desc')->select();

        if ($request->status) :
            $query->where('status', $request->status);
        endif;

        $query->skip($skipCount);
        $query->take($pageSize);

        $orders = $query->select('id','user_id', 'basket_id', 'coupon_id', 'total_price', 'discount', 'order_date', 'order_time', 'address_id', 'status', 'user_deliverd_time')->get();
        
        $orders->map(function ($q) {

            if($q->coupon_id != null):
                $coupon = Coupon::find($q->coupon_id);
                $q->couponCode= $coupon ? $coupon->code : '';
            endif;
            $address = UserAddress::find($q->address_id);
            $q->address= $address ? $address->address : '';    

            $items = Item::where('basket_id',$q->basket_id)->select('id','itemable_id','amount','itemable_type')->get();
            $items->map(function ($q) {
                $product = Product::find($q->itemable_id);
                $offer = Offer::find($q->itemable_id);
                if($q->itemable_type == 'App\Product'):
                    $q->product_name = $product->name;
                    $q->product_price = $product->price;
                    $q->product_image= Request()->root() . '/files/products/' . $product->image ;
                    //$q->offer_name = null;
                    $q->offer_price = null;
                else:
                    $offer_product = Product::find($offer->product_id);
                    //$q->offer_name = $offer->name;
                    $q->offer_price = $offer->price;
                    $q->product_name = $offer_product->name;
                    $q->product_price = $offer_product->price;
                    $q->product_image= Request()->root() . '/files/products/' . $offer_product->image ;

                endif;
            });

            $q->items = $items;        
                  
            /*status:
                0 => 'new'
                1 => 'accepted'
                2 => 'rejected'
                3 => 'finished'
            */
            
         });

        return response()->json([
            'status' => true,
            //'message' => $status,
            'data' => $orders
        ]);
    }
    
    public function getBasket(Request $request){
        $rules = [
            'playerId' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);

            return response()->json(['status'=>false,'data' => $error_arr]);
        }
        
        $basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();
//`itemable_id`, `itemable_type`, `amount`, `type`, `basket_id`
        if($basket):
            //$items = Item::where('basket_id',$basket->id)->get();
            $items = Item::where('basket_id',$basket->id)->select('id','itemable_id','amount','itemable_type')->get();
            $items->map(function ($q) {
                $product = Product::find($q->itemable_id);
                $offer = Offer::find($q->itemable_id);
                if($q->itemable_type == 'App\Product'):
                    $q->product_name = $product->name;
                    $q->product_price = $product->price;
                    $q->product_image= Request()->root() . '/files/products/' . $product->image ;
                    //$q->offer_name = null;
                    $q->offer_price = null;
                else:
                    $offer_product = Product::find($offer->product_id);
                    //$q->offer_name = $offer->name;
                    $q->offer_price = $offer->price;
                    $q->product_name = $offer_product->name;
                    $q->product_price = $offer_product->price;
                    $q->product_image= Request()->root() . '/files/products/' . $offer_product->image ;

                endif;
            });
        else:
            $items = [];
        endif;

        return response()->json([
            'status' => true,
            'data' =>$items
        ]);
    }

    public function saveBasket(Request $request){

        $rules = [
            'playerId' => 'required',
            'items' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);

            return response()->json(['status'=>false,'data' => $error_arr]);
        }
        
        $basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();

        if(! $basket):
            $basket = new Basket();
            $basket->device = $request->playerId;
            $basket->is_ordered = 0 ;
            $basket->save();
        endif;

        $old_items = Item::where('basket_id',$basket->id)->get();

        if(count($old_items) > 0):
            foreach($old_items as $item){
                $item->delete();
            }
        endif;

        if($request->has('items')){
            $items = json_decode($request->items);
            if(count($items) > 0){
                //`itemable_id`, `itemable_type`, `amount`, `type`, `basket_id`
                foreach($items as $item){
                    $model = new Item;
                    $model->itemable_id = $item->item_id;
                    if($item->type == 'offer'):
                        $model->itemable_type = 'App\Offer';
                        $model->type = 'offer';
                    else:
                        $model->itemable_type = 'App\Product';
                        $model->type = 'product';
                    endif;
                    $model->amount = $item->amount;
                    $model->basket_id = $basket->id;
                    $model->save();
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'تم اضافة المنتجات للسلة بنجاح'
        ]);
    }


    public function saveOrder(Request $request){
        
        $user = auth()->user();
        
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'مستخدم غير مسجل بالتطبيق',
                'data' => []
            ]);
        }

        $rules = [
            'playerId' => 'required',
            'order_date' => 'date_format:"Y-m-d"|required|after_or_equal:today',
            'order_time' => 'date_format:"H:i:s"|required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);

            return response()->json(['status'=>false,'data' => $error_arr]);
        }

        if($request->addressId){

            $userAddress = UserAddress::where('id',$request->addressId)->first();

            if(!$userAddress){
                return response()->json([
                    'status' => false,
                    'message' => 'عنوان غير مسجل ضمن عناوين المستخدم',
                    'data' => []
                ]);
            }
        }else{

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

            $userAddress = new UserAddress();
            $userAddress->user_id = $user->id ;
            $userAddress->lat = $request->lat ;
            $userAddress->lng = $request->lng ;
            $userAddress->address = $request->address;
            $userAddress->save();

        }

        $basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();
        if(!$basket){
            return response()->json([
                'status' => false,
                'message' => 'لا توجد منتجات خاصة بالمستخدم',
                'data' => []
            ]);
        }

        $items = Item::where('basket_id',$basket->id)->get();

        if(! count($items) > 0){
            return response()->json([
                'status' => false,
                'message' => 'السلة فارغة',
                'data' => []
            ]);
        }

        $orderTime = date('H:i:s', strtotime($request->order_time));
        $mytime = Carbon::now();

        if($request->order_date == $mytime->toDateString()){
            if($orderTime <= $mytime->toTimeString()){
                return response()->json([
                    'status' => false,
                    'message' => 'غير متاح الحجز فى وقت سابق',
                    'data' => []
                ]);
            }
        }

        $workDays = workDay::select('day','from','to')->get();
        $days = $workDays->pluck('day')->toArray();
        $day = date('D', strtotime($request->order_date));
        //return response()->json($day);

        if(!in_array($day, $days)){

            return response()->json([
                'status' => false,
                'message' => 'هذا اليوم خارج نطاق ايام العمل',
                'data' => $day
            ]);
        }

        $time_range = $workDays->where('day',$day)->first();

        if(!( $request->order_time >= $time_range->from && $request->order_time <= $time_range->to )){

            return response()->json([
                'status' => false,
                'message' => 'الوقت خارج دوام العمل',
                'data' => $day
            ]);
        }

        //`user_id`, `basket_id`, `coupon_id`, `total_price`, `discount`, `order_date`, `order_time`, `address_id`, `status`, `user_deliverd_time`
        $newModel = new Order();
        $newModel->user_id = $user->id;
        $newModel->basket_id = $basket->id;
        $newModel->order_date = $request->order_date;
        $newModel->order_time = $request->order_time;

        $cost = 0;
        //return response()->json($items);
        foreach($items as $item){
            if($item->itemable_type == 'App\Product'):
                $product = Product::find($item->itemable_id);  
                if($product):
                    $cost += $product->price * $item->amount;
                endif;
            else:
                $offer = Offer::find($item->itemable_id);  
                if($offer):
                    $cost += $offer->price * $item->amount;
                endif;
            endif;  
        }

        $newModel->total_price = $cost + setting()->getBody('delivery');
        $newModel->address_id = $userAddress->id;
        $newModel->user_deliverd_time = null;
        $newModel->status = 0;
        $newModel->discount = 0;
        $newModel->coupon_id = null ;

        $newModel->save();
        
        if($request->couponCode && $request->couponCode != ''){
            // $discount = Coupon::where('code',$request->couponCode)->where('from','>=',date('Y-m-d'))->where('to','>=',date('Y-m-d'))->first();

            $discount = Coupon::where('code',$request->couponCode)->where('to','>=',date('Y-m-d'))->first();
            if($discount){
                $orders = Order::where('user_id',$user->id)->where('coupon_id',$discount->id)->count();
                $diff = $discount->times - $orders ;
                if($diff > 0){
                    $newModel->discount = ($discount->ratio * $cost) / 100 ;
                    $newModel->coupon_id = $discount->id;
                    
                }else{
                    $newModel->discount = 0;
                    $newModel->coupon_id = null;

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
                $newModel->coupon_id = null ;
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
            $newModel->coupon_id = null;
        }
        
        $newModel->save();

        $basket->is_ordered = 1;
        $basket->save();
        
        if(app()->getlocale() == 'ar'):
            
                $title = $user->name;
                $msg = 'تم اضافة طلب جديد';
                
        else:
            $title = $user->name ;
            $msg = 'new order has been added';
        endif;
        
        //$noti =$this->sendSingleNotification($title , $msg , $company->user_id ,'new_order' ,$newModel->id);
            
        return response()->json([
            'status' => true,
            'message' => 'تم اضافة الطلب بنجاح',
            'data' => []
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
                'message' => 'الطلب غير موجود' ,
                'data' => []
            ]);
        }
        
        if($order->user_id != $user->id){
            if(app()->getlocale() == 'ar'):
                $msg = 'عفوا لا يمكنك حذف هذا الطلب';
            else:
                $msg = 'you cannot delete this order';
            endif;
            
            return response()->json([
                'status' => false ,
                'message' => $msg ,
                'data' => []
            ]);
        }
        
        if($order->status != 0){
            if(app()->getlocale() == 'ar'):
                $msg = 'لا يمكنك حذف الطلب حيث تم تغير حالته';
            else:
                $msg = 'you cannot delete this order as its status has been changed';
            endif;
            
            return response()->json([
                'status' => false ,
                'message' => $msg ,
                'data' => []
            ]);
        }

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
