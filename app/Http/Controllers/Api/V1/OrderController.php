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
use App\WorkDay;
use App\Coupon;
use App\User;
use App\Product;
use App\UserAddress;
use App\City;

// use App\Notification;
// use LaravelFCM\Message\OptionsBuilder;
// use LaravelFCM\Message\PayloadDataBuilder;
// use LaravelFCM\Message\PayloadNotificationBuilder;
// use FCM;

use Carbon\Carbon;

class OrderController extends Controller
{
    
    public function getUserRecentOrder(Request $request){

        $user = auth()->user();
        
        if(!$user){
            return response()->json([
                'status' => 400,
                'message' => 'user not found',
                'errors' => ['user not found'],
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
                $q->offer_price = '';
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

        $data =  json_decode(json_encode($order),true);
            $data  =array_filter($data, function($value){
               return isset($value);
           });
        
        return response()->json([
            'status' => 200,
            //'message' => $status,
            'data' => $data
        ]);
    }

    public function getUserOrders(Request $request){

        $user = auth()->user();
        
        if(!$user){
            return response()->json([
                'status' => 400,
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

        $orders = $query->select('id','total_price', 'status','user_id', 'basket_id')->get();
        
        $orders->map(function ($q) {

            $items = Item::where('basket_id',$q->basket_id)->select('id','itemable_id','amount','itemable_type')->get();
            $items->map(function ($q) {
                $product = Product::find($q->itemable_id);
                $offer = Offer::find($q->itemable_id);
                if($q->itemable_type == 'App\Product'):
                    $q->product_name = $product->name;
                    $q->product_price = $product->price;
                    $q->offer_price = '';
                else:
                    $offer_product = Product::find($offer->product_id);
                    $q->offer_price = $offer->price;
                    $q->product_name = $offer_product->name;
                    $q->product_price = $offer_product->price;
                endif;
            });

            $item_names = [];
            if(count($items)>0){
                foreach($items as $item){
                    array_push($item_names, $item->product_name);
                }
            }

            $q->items_names = $item_names;
            $q->items = $items;      
                  
            /*status:
                0 => 'new'
                1 => 'accepted'
                2 => 'rejected'
                3 => 'finished'
            */
            
         });

        $user_orders = [];

        if(count($orders) > 0):
            $user_orders = $orders->map(function($q){
                   $data = json_decode(json_encode($q),true);
                  return $q= array_filter($data,function($value){
                       return isset($value);
                   });
               });
        endif;

        return response()->json([
            'status' => 200,
            //'message' => $status,
            'data' => $user_orders,
        ]);
    }
    
    public function getBasket(Request $request){
        $rules = [
            'playerId' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);

            return response()->json(['status'=>400,'errors' => $validator->errors()->all()]);
        }

        $user = auth()->user();

        if($user):
            //$basket = Basket::where('user_id',$user->id)->where('is_ordered',0)->first();
            $basket = Basket::where('device',$request->playerId)->orWhere('user_id',$user->id)->where('is_ordered',0)->first();
        else:
            $basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();
        endif;
        
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
                    $q->offer_price = '';
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

        $user_items = [];
        if(count($items) > 0):
            $user_items = $items->map(function($q){
                   $data = json_decode(json_encode($q),true);
                  return $q= array_filter($data,function($value){
                       return isset($value);
                   });
               });
        endif;

        return response()->json([
            'status' => 200,
            'data' =>$user_items
        ]);
    }

    public function saveBasket(Request $request){

        $rules = [
            'playerId' => 'required',
            'items' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            //$error_arr = validateRules($validator->errors(), $rules);

            return response()->json(['status'=>400,'errors' => $validator->errors()->all()]);
        }
        $user = auth()->user();
        if($user):
            $basket = Basket::where('device',$request->playerId)->orWhere('user_id',$user->id)->where('is_ordered',0)->first();
            $userId = $user->id;
        else:
            $basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();
            $userId = null;
        endif;

        if(! $basket):
            $basket = new Basket();
            $basket->device = $request->playerId;
            $basket->user_id = $userId;
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
            'status' => 200,
            'message' => 'تم اضافة المنتجات للسلة بنجاح'
        ]);
    }


    public function saveOrder(Request $request){
        
        $user = auth()->user();
        
        if(!$user){
            return response()->json([
                'status' => 400,
                'errors' => ['مستخدم غير مسجل بالتطبيق'],
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

            return response()->json(['status'=>400,'errors' => $validator->errors()->all()]);
        }

        if($request->addressId){

            $userAddress = UserAddress::where('id',$request->addressId)->first();

            if(!$userAddress){
                return response()->json([
                    'status' => 400,
                    'message' => 'عنوان غير مسجل ضمن عناوين المستخدم',
                    'errors' => ['عنوان غير مسجل ضمن عناوين المستخدم'],
                    'data' => []
                ]);
            }
        }else{

            $rules = [
                //'lat' => 'required',
                //'lng' => 'required',
                'address' => 'required',
                'cityId' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                //$errors = validateRules($validator->errors(), $rules);
                return response()->json(['status'=>400, 'errors' => $validator->errors()->all()]);
            }

            $userAddress = new UserAddress();
            $userAddress->user_id = $user->id ;
            $userAddress->lat = $request->lat ? $request->lat : '' ;
            $userAddress->lng = $request->lng ? $request->lng : '' ;
            $userAddress->address = $request->address;
            $userAddress->city = $request->cityId;
            $userAddress->save();

        }

        $address_city = City::where('id',$userAddress->city)->first();
        if(!$address_city):
            return response()->json(['status'=>400, 'errors' => ['المدينة غير مسجلة بالتطبيق']]);
        endif;

        if($address_city->status != 1){
            return response()->json(['status'=>400, 'errors' => ['المدينة غير متاحة '] , 'data' => ['cityId' =>$address_city->id , 'cityName' => $address_city->name ] ]);
        }

        $basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();
        if(!$basket){
            return response()->json([
                'status' => 400,
                'message' => 'لا توجد منتجات خاصة بالمستخدم',
                'data' => []
            ]);
        }

        $items = Item::where('basket_id',$basket->id)->get();

        if(! count($items) > 0){
            return response()->json([
                'status' => 400,
                'message' => 'السلة فارغة',
                'errors' => ['السلة فارغة'],
                'data' => []
            ]);
        }

        $orderTime = date('H:i:s', strtotime($request->order_time));
        $mytime = Carbon::now();

        if($request->order_date == $mytime->toDateString()){
            if($orderTime <= $mytime->toTimeString()){
                return response()->json([
                    'status' => 400,
                    'message' => 'غير متاح الحجز فى وقت سابق',
                    'errors' => ['غير متاح الحجز فى وقت سابق'],
                    'data' => []
                ]);
            }
        }

        $workDays = WorkDay::select('day','from','to')->get();
        $days = $workDays->pluck('day')->toArray();
        $day = date('D', strtotime($request->order_date));
        //return response()->json($day);

        if(!in_array($day, $days)){

            return response()->json([
                'status' => 400,
                'message' => 'هذا اليوم خارج نطاق ايام العمل',
                'errors' => ['هذا اليوم خارج نطاق ايام العمل'],
                'data' => $day
            ]);
        }

        $time_range = $workDays->where('day',$day)->first();

        if(!( $request->order_time >= $time_range->from && $request->order_time <= $time_range->to )){

            return response()->json([
                'status' => 400,
                'message' => 'الوقت خارج دوام العمل',
                'errors' => ['الوقت خارج دوام العمل'],
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

        //$cost += setting()->getBody('delivery');

        //$newModel->total_price = $cost;
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
                        'status' => 200,
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
                    'status' => 200,
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
            'status' => 200,
            'message' => 'تم اضافة الطلب بنجاح',
            'data' => []
        ]);
    }
    
    
    public function getOrderDetails(Request $request){
        
        
        // $now = Carbon::now();
        // $current_time = $now->toTimeString();

        $user = auth()->user();
        
        if(!$user){
            return response()->json([
                'status' => 400,
                'errors' => ['مستخدم غير مسجل بالتطبيق'],
                'message' => 'مستخدم غير مسجل بالتطبيق',
                'data' => []
            ]);
        }
        
        $order = Order::where('user_id',$user->id)->where('id',$request->orderId)->orderBy('id','Desc')->select('id','user_id', 'basket_id', 'coupon_id', 'total_price', 'discount', 'order_date', 'order_time', 'address_id', 'status', 'user_deliverd_time')->first();

        if(!$order){
            
            return response()->json([
                'status' => 400 ,
                'message' => 'الحجز غير موجود' ,
                'errors' => ['الحجز غير موجود'] ,
            ]);
        }

        if($order->coupon_id != null):
            $coupon = Coupon::find($order->coupon_id);
            $order->couponCode= $coupon ? $coupon->code : '';
        endif;
        $address = UserAddress::find($order->address_id);
        if($address):
            $order->address= $address->address ;
            $city = City::find($address->city);
            $order->city= $city ? $city->name : '' ;
        else:    
            $order->address= '';
            $order->city= '' ;

        endif;    

        $items = Item::where('basket_id',$order->basket_id)->select('id','itemable_id','amount','itemable_type')->get();
        $items->map(function ($q) {
            $product = Product::find($q->itemable_id);
            $offer = Offer::find($q->itemable_id);
            if($q->itemable_type == 'App\Product'):
                $q->product_name = $product->name;
                $q->product_price = $product->price;
                $q->product_image= Request()->root() . '/files/products/' . $product->image ;
                $q->offer_price = '';
                $q->item_cost = $product->price * $q->amount ;
            else:
                $offer_product = Product::find($offer->product_id);
                $q->offer_price = $offer->price;
                $q->product_name = $offer_product->name;
                $q->product_price = $offer_product->price;
                $q->product_image= Request()->root() . '/files/products/' . $offer_product->image ;
                $q->item_cost = $offer->price * $q->amount ;

            endif;
        });

        $order->username = $user->name;
        $order->user_phone = $user->phone;
        $order ->delivery_cost = setting()->getBody('delivery');

        $order->items = $items;

        $data = json_decode(json_encode($order),true);
          return $order= array_filter($data,function($value){
               return isset($value);
           });
        
        return response()->json([
                'status' => 200 ,
                'message' => '' ,
                'data' => $data
            ]);
    }

}
