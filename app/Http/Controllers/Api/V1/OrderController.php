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
use App\MeasurementUnit;

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
            ]);
        }
        
        $order = Order::where('user_id',$user->id)->orderBy('id','Desc')->select('id','user_id','name','phone','payment_type', 'basket_id', 'coupon_id', 'total_price','price','tax','delivery', 'discount', 'order_date', 'order_time', 'address_id', 'status', 'user_deliverd_time')->first();

        if(!$order){
            return response()->json([
                'status' => 400,
                'message' => 'لا يوجد طلبات للمستخدم',
                'errors' => ['لا يوجد طلبات للمستخدم'],
            ]);
        }

        if($order->status == 0): 
            $order->order_status = 'جديد';
        elseif($order->status ==1): 
            $order->order_status = 'جارى التجهيز';
        elseif($order->status ==2): 
            $order->order_status = 'مرفوض';
        elseif($order->status ==3): 
            $order->order_status = 'جارى التوصيل';
        else: 
            $order->order_status = 'مكتمل';
        endif;

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
            if($product):
                $measurementUnit = MeasurementUnit::find($product->measurement_id);
            else:
                $measurementUnit = null;
            endif;
            $q->measurementUnit = $measurementUnit != null ? $measurementUnit->name : '' ;

            if($q->itemable_type == 'App\Product'):
                $q->product_name = $product->name;
                $q->product_price = $product->price;
                $q->product_image= Request()->root() . '/files/products/' . $product->image ;
                //$q->offer_name = null;
                $q->offer_price = '';
            else:
                $offer_product = Product::find($offer->product_id);
                //$q->offer_name = $offer->name;
                $q->offer_price = (string)$offer->price;
                $q->product_name = $offer_product->name;
                $q->product_price = $offer_product->price;
                $q->product_image= Request()->root() . '/files/products/' . $offer_product->image ;

            endif;
        });

        $item_names = [];
        if(count($items)>0){
            foreach($items as $item){
                array_push($item_names, $item->product_name);
            }
        }

        $order->items_names = $item_names;

        $order->data = $items;

        $data =  json_decode(json_encode($order),true);
            $data  =array_filter($data, function($value){
               return isset($value);
           });
        
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function getUserFinishedOrders(Request $request){

        $user = auth()->user();
        
        if(!$user){
            return response()->json([
                'status' => 400,
                'errors' => ['مستخدم غير مسجل بالتطبيق'],
                'message' => 'user not found',
            ]);
        }

        $pageSize = $request->get('pageSize', 15);
        $skipCount = $request->get('skipCount', 0);
        
        $query = Order::where('user_id',$user->id)->orderBy('id','Desc')->where('status',3)->select();

        if ($request->status) :
            $query->where('status', $request->status);
        endif;

        $query->skip($skipCount);
        $query->take($pageSize);

        $orders = $query->select('id','total_price', 'price','tax','delivery','status','user_id','name','phone','payment_type', 'basket_id')->get();
        
        if(count($orders)>0){
        $orders->map(function ($q) {

            if($q->status == 0): 
                $q->order_status = 'جديد';
            elseif($q->status ==1): 
                $q->order_status = 'جارى التجهيز';
            elseif($q->status ==2): 
                $q->order_status = 'مرفوض';
            elseif($q->status ==3): 
                $q->order_status = 'جارى التوصيل';
            else: 
                $q->order_status = 'مكتمل';
            endif;

            $items = Item::where('basket_id',$q->basket_id)->select('id','itemable_id','amount','itemable_type')->get();
            if(count($items)>0){
                $items->map(function ($q) {
                    $product = Product::find($q->itemable_id);
                    $offer = Offer::find($q->itemable_id);
                    if($product):
                        $measurementUnit = MeasurementUnit::find($product->measurement_id);
                    else:
                        $measurementUnit = null;
                    endif;
                    if($q->itemable_type == 'App\Product'):
                        $q->product_name = $product ? $product->name : '';
                        $q->product_price = $product ? (string)$product->price : '0';
                        $q->offer_price = '';
                    else:
                        $offer_product = Product::find($offer->product_id);
                        $q->offer_price = $offer ? (string)$offer->price : '0';
                        $q->product_name = $offer_product ? $offer_product->name : '';
                        $q->product_price = $offer_product ? (string)$offer_product->price : '0';
                    endif;
                        $q->measurementUnit = $measurementUnit != null ? $measurementUnit->name : '' ;

                });
            }

            $item_names = [];
            if(count($items)>0){
                foreach($items as $item){
                    array_push($item_names, $item->product_name);
                }
            }

            $q->items_names = $item_names;
            $q->data = $items;      
                  
            /*status:
                0 => 'new'
                1 => 'accepted'
                2 => 'rejected'
                3 => 'finished'
            */
            
         });
    }

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

    public function getUserOrders(Request $request){

        $user = auth()->user();
        
        if(!$user){
            return response()->json([
                'status' => 400,
                'errors' => ['مستخدم غير مسجل بالتطبيق'],
                'message' => 'user not found',
            ]);
        }

        $pageSize = $request->get('pageSize', 15);
        $skipCount = $request->get('skipCount', 0);
        
        $query = Order::where('user_id',$user->id)->orderBy('id','Desc')->whereIn('status',[0,1])->select();

        if ($request->status) :
            $query->where('status', $request->status);
        endif;

        $query->skip($skipCount);
        $query->take($pageSize);

        $orders = $query->select('id','total_price','price','tax','delivery', 'status','user_id','name','phone','payment_type', 'basket_id')->get();
        
        if(count($orders)>0){

        $orders->map(function ($q) {

            if($q->status == 0): 
                $q->order_status = 'جديد';
            elseif($q->status ==1): 
                $q->order_status = 'جارى التجهيز';
            elseif($q->status ==2): 
                $q->order_status = 'مرفوض';
            elseif($q->status ==3): 
                $q->order_status = 'جارى التوصيل';
            else: 
                $q->order_status = 'مكتمل';
            endif;

            $total = 0;

            $items = Item::where('basket_id',$q->basket_id)->select('id','itemable_id','amount','itemable_type')->get();

            if(count($items)>0){
                $items->map(function ($q) {
                    $product = Product::find($q->itemable_id);
                    $offer = Offer::find($q->itemable_id);
                    if($product):
                        $measurementUnit = MeasurementUnit::find($product->measurement_id);
                    else:
                        $measurementUnit = null;
                    endif;
                    if($q->itemable_type == 'App\Product'):
                        $q->product_name = $product ? $product->name : '';
                        $q->product_price = $product ? (string)$product->price : '0';
                        $q->offer_price = '';
                        $q->total = $product ? $q->amount * $product->price : 0;
                    else:
                    	if($offer):
                        $offer_product = Product::find($offer->product_id);
                        $q->offer_price = $offer ? (string)$offer->price : '0';
                        $q->product_name = $offer_product ? $offer_product->name : '';
                        $q->product_price = $offer_product ? (string)$offer_product->price : '0';
                        $q->total = $offer ? $q->amount * $offer->price : 0;
                    endif;
                    endif;
                        $q->measurementUnit = $measurementUnit != null ? $measurementUnit->name : '' ;

                });
            }

            $item_names = [];
            if(count($items)>0){
                foreach($items as $item){
                    array_push($item_names, $item->product_name);
                    //$total += $item->total;
                }
            }

            $q->items_names = $item_names;
            $q->data = $items;  
            $total_pricee = number_format((float)$q->total_price, 2, '.', '');
            $q->total_price = (string)$total_pricee;  

            // $total += setting()->getBody('delivery');
            // $tax = $total * setting()->getBody('tax')/100 ;
             //$q->tax = $total * setting()->getBody('tax')/100 ;
             //$q->delivery = setting()->getBody('delivery') ;
            // $total += $tax; 
            // $q->total = $total; 
                  
            /*status:
                0 => 'new'
                1 => 'accepted'
                2 => 'rejected'
                3 => 'finished'
            */
            
         });
    }

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

        if($request->api_token != ''):
            $user = User::where('api_token',$request->api_token)->first();
        else:
            $user = null;
        endif;

        if($user):
            //$basket = Basket::where('user_id',$user->id)->where('is_ordered',0)->first();
            // $basket = Basket::where('device',$request->playerId)->orWhere('user_id',$user->id)->where('is_ordered',0)->first();
            $basket = Basket::where('user_id',$user->id)->where('is_ordered',0)->first();
        else:
            $basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();
        endif;
        
//`itemable_id`, `itemable_type`, `amount`, `type`, `basket_id`
        $total = 0;
        $total_price = 0;
        $tax = 0;
        $taxx = 0;
        $total_offer = 0;

        if($basket):
            //$items = Item::where('basket_id',$basket->id)->get();
            $items = Item::where('basket_id',$basket->id)->select('id','itemable_id','amount','item_price','itemable_type')->get();
            if(count($items) > 0){
                $items->map(function ($q) use ($total){
                    $product = Product::find($q->itemable_id);
                    $offer = Offer::find($q->itemable_id);
                    if($product):
                        $measurementUnit = MeasurementUnit::find($product->measurement_id);
                    else:
                        $measurementUnit = null;
                    endif;

                    if($q->itemable_type == 'App\Product'):
                        $q->product_name = $product ? $product->name : '';
                        $q->product_price = $product ? (string)$product->price : '0';
                        $q->product_image= Request()->root() . '/files/products/' . $product->image ;
                        //$q->offer_name = null;
                        $q->offer_price = '0';
                        $q->measurementUnit = $measurementUnit != null ? $measurementUnit->name : '' ;

                        $q->insale_product_price = '0';
                        $q->sale = '0';
                        $q->sale_percntage = '0';
                        $q->itemType = 1;

                        $q->total = $q->amount * $product->price;
                    else:
                        $offer_product = Product::find($offer->product_id);
                        //$q->offer_name = $offer->name;
                        $q->offer_price = $offer ? (string)$offer->price : '0';
                        $q->product_price = $offer ? (string)$offer->product_price : '0';
                        $q->product_name = $offer_product ? $offer_product->name : '';
                        //$q->product_price = $offer_product ? (string)$offer_product->price: '0';
                        $q->product_image= Request()->root() . '/files/products/' . $offer_product->image ;
                        $q->measurementUnit = $measurementUnit != null ? $measurementUnit->name : '' ;
                        $q->insale_product_price = $offer ? (string)$offer->insale_product_price : '0';
                        $q->sale = $offer ? (string)$offer->sale : '0';
                        $q->sale_percntage = $offer ? (string)$offer->sale_percntage : '0';
                        $q->itemType = 0;

                        $q->total = $q->amount * $offer->price;

                    endif;
                });

                // foreach($items as $item){
                //     $total += $item->total;

                // }
            }

            $tax = $total * setting()->getBody('tax')/100 ;
            $total += setting()->getBody('delivery');
            
            $total += $tax;

            //
        $cost = 0.00;
        if(count($items) > 0){
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
                        $total_offer += $offer->sale * $offer->amount *$item->amount;
                    endif;
                endif;  
            }
        }
        $taxx=0;
        $taxx = $cost * setting()->getBody('tax')/100 ;
        $delivery = setting()->getBody('delivery');
        $price = $cost;
        $cost += $taxx;
        $total_price = $cost + setting()->getBody('delivery');
            //
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
            'data' =>$user_items,
            'delivery_cost' => setting()->getBody('delivery'),
            'tax_cost' => $taxx ,
            'total' => $total_price ,
            'total_discount' => $total_offer
        ]);
    }

    public function saveBasketold(Request $request){

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
            return response()->json(['status'=>202, 'errors' => ['المدينة غير متاحة '] , 'data' => ['cityId' =>$address_city->id , 'cityName' => $address_city->name ] ]);
        }

        //$basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();
        //$user = User::where('api_token',$request->api_token)->first();

        if($user):
            $basket = Basket::where('user_id',$user->id)->where('is_ordered',0)->first();
        else:
            $basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();
        endif;
        if(!$basket){
            return response()->json([
                'status' => 400,
                'errors' => ['لا توجد منتجات خاصة بالمستخدم'],
                'message' => 'لا توجد منتجات خاصة بالمستخدم',
            ]);
        }

        $items = Item::where('basket_id',$basket->id)->get();

        if(! count($items) > 0){
            return response()->json([
                'status' => 400,
                'message' => 'السلة فارغة',
                'errors' => ['السلة فارغة'],
            ]);
        }

        //update items price 
        foreach ($items as $item) {
            if($item->type == 'offer'):
                $offer = Offer::find($item->itemable_id);
                $item->item_price = $offer ? $offer->price : '';
            else:
                $product = Product::find($item->itemable_id);
                $item->item_price = $product ? $product->price : ''; 
            endif;
            $item->save();
        }

        $orderTime = date('H:i:s', strtotime($request->order_time));
        $mytime = Carbon::now();

        if($request->order_date == $mytime->toDateString()){
            if($orderTime <= $mytime->toTimeString()){
                return response()->json([
                    'status' => 400,
                    'message' => 'غير متاح الحجز فى وقت سابق',
                    'errors' => ['غير متاح الحجز فى وقت سابق'],
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
                //'data' => $day
            ]);
        }

        $time_range = $workDays->where('day',$day)->first();

        if(!( $request->order_time >= $time_range->from && $request->order_time <= $time_range->to )){

            return response()->json([
                'status' => 400,
                'message' => 'الوقت خارج دوام العمل',
                'errors' => ['الوقت خارج دوام العمل'],
                //'data' => $day
            ]);
        }

        //`user_id`, `basket_id`, `coupon_id`, `total_price`, `discount`, `order_date`, `order_time`, `address_id`, `status`, `user_deliverd_time`
        $newModel = new Order();
        $newModel->user_id = $user->id;
        $newModel->basket_id = $basket->id;
        $newModel->order_date = $request->order_date;
        $newModel->order_time = $request->order_time;
        $newModel->name = $request->name ? $request->name : '';
        $newModel->phone = $request->phone ? $request->phone : '';
        $newModel->payment_type = $request->payment_type ? $request->payment_type : 1;

        $cost = 0.00;
        $total_offer = 0.00;
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
                    $total_offer += $offer->sale * $offer->amount *$item->amount;
                endif;
            endif;  
        }

        //$cost += setting()->getBody('delivery');

        //$newModel->total_price = $cost;
        $tax = $cost * setting()->getBody('tax')/100 ;
        $newModel->tax = $tax;
        $newModel->delivery = setting()->getBody('delivery');
        $newModel->price = $cost;
        $cost += $tax;
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
        
        $newModel->total_price = $newModel->total_price - $newModel->discount;
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
            ]);
        }
        
        $order = Order::where('user_id',$user->id)->where('id',$request->orderId)->orderBy('id','Desc')->select('id','user_id','name','phone','payment_type', 'basket_id', 'coupon_id', 'total_price','price','tax','delivery', 'discount', 'order_date', 'order_time', 'address_id', 'status', 'user_deliverd_time')->first();

        $order->tax_cost = intval($order->tax) ;
        $order->delivery_cost = intval($order->delivery) ;
        $order_status_list = [];
        if($order->status == 0): 
            $order->order_status = 'جديد';
            $order->order_status_list = ['status1'=>'جديد'];
            $s = (object) array('status' => 'جديد');
            array_push($order_status_list, $s);
        elseif($order->status ==1): 
            $order->order_status = 'جارى التجهيز';
            $order->order_status_list = array('status1'=>'جديد','status2'=>'جارى التجهيز');
            //array_push($order_status_list, {'status':'جديد'});
            //array_push($order_status_list, {'status':'جارى التجهيز'});
            $s = (object) array('status' => 'جديد');
            array_push($order_status_list, $s);
            $s = (object) array('status' => 'جارى التجهيز');
            array_push($order_status_list, $s);

        elseif($order->status ==2): 
            $order->order_status = 'مرفوض';
            $order->order_status_list = array('status1'=>'جديد','status2'=>'مرفوض');
            // array_push($order_status_list, {'status':'جديد'});
            // array_push($order_status_list, {'status':'مرفوض'});
            $s = (object) array('status' => 'جديد');
            array_push($order_status_list, $s);
            $s = (object) array('status' => 'مرفوض');
            array_push($order_status_list, $s);

        elseif($order->status ==3): 
            $order->order_status = 'جارى التوصيل';
            $order->order_status_list = array('status1'=>'جديد','status2'=>'جارى التجهيز','status3'=>'جارى التوصيل');
            $s = (object) array('status' => 'جديد');
            array_push($order_status_list, $s);
            $s = (object) array('status' => 'جارى التجهيز');
            array_push($order_status_list, $s);
            $s = (object) array('status' => 'جارى التوصيل');
            array_push($order_status_list, $s);
            // array_push($order_status_list, {'status':'جديد'});
            // array_push($order_status_list, {'status':'جارى التجهيز'});
            // array_push($order_status_list, {'status':'جارى التوصيل'});

        else: 
            $order->order_status = 'مكتمل';
            $order->order_status_list = array('status1'=>'جديد','status2'=>'جارى التجهيز','status3'=>'جارى التوصيل','status4'=>'مكتمل');
            // array_push($order_status_list, {'status':'جديد'});
            // array_push($order_status_list, {'status':'جارى التجهيز'});
            // array_push($order_status_list, {'status':'جارى التوصيل'});
            // array_push($order_status_list, {'status':'مكتمل'});

        endif;
        $order->status_list = $order_status_list;

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
        $total_offer = 0 ;
        $items = Item::where('basket_id',$order->basket_id)->select('id','itemable_id','amount','item_price','itemable_type')->get();

        if(count($items) > 0){
            $items->map(function ($q) use($total_offer){
                $product = Product::find($q->itemable_id);
                $offer = Offer::find($q->itemable_id);
                if($product):
                    $measurementUnit = MeasurementUnit::find($product->measurement_id);
                else:
                    $measurementUnit = null;
                endif;
                $q->measurementUnit = $measurementUnit != null ? $measurementUnit->name : '' ;

                if($q->itemable_type == 'App\Product'):
                    $q->product_name = $product ? $product->name : '';
                    $q->product_price = $product ? (string)$product->price :(string) 0;
                    $q->product_image= Request()->root() . '/files/products/' . $product->image ;
                    $q->offer_price = (string) 0;
                    $q->total = $q->item_price * $q->amount ;
                    $q->insale_product_price = '0';
                    $q->sale = '0';
                    $q->sale_percntage = '0';
                else:
                    $offer_product = Product::find($offer->product_id);
                    $q->offer_price = $offer ? (string)$offer->price : (string)0;
                    $q->product_name = $offer_product ? $offer_product->name : '';
                    $q->product_price = $offer_product ? (string)$offer_product->price : (string)0;
                    $q->product_image= Request()->root() . '/files/products/' . $offer_product->image ;
                    $q->total = $q->item_price * $q->amount ;

                    $q->insale_product_price = $offer ? (string)$offer->insale_product_price : '0';
                    $q->sale = $offer ? (string)$offer->sale : '0';
                    $q->sale_percntage = $offer ? (string)$offer->sale_percntage : '0';
                //     if($offer):
                //     $total_offer += $offer->sale * $offer->amount *$q->amount;
                // endif;

                endif;
            });
        }

        if(count($items) > 0){
            foreach($items as $item){
                if($item->itemable_type == 'App\Product'):
                    $product = Product::find($item->itemable_id);  
                    if($product):
                        //$cost += $product->price * $item->amount;
                    endif;
                else:
                    $offer = Offer::find($item->itemable_id);  
                    if($offer):
                        //$cost += $offer->price * $item->amount;
                        $total_offer += $offer->sale * $offer->amount *$item->amount;
                    endif;
                endif;  
            }
        }
        $order->total_offer = $total_offer;
        $total_discountt = $order->discount + $total_offer ;
        $dis = number_format((float)$total_discountt, 2, '.', '');
        //$dis = round((float)$total_discountt, 2);
        $order->total_discount = (string)$dis ;
        $total_pricee = number_format((float)$order->total_price, 2, '.', '');
        $order->total_price = (string)$total_pricee;


        // $order->username = $user->name;
        // $order->user_phone = $user->phone;
        $order->delivery_cost = setting()->getBody('delivery');

        $order->data = $items;

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


    public function saveBasket(Request $request)
    {
        $rules = [
            'playerId' => 'required',
            'item_id' => 'required',
            'amount' => 'required',
            'type' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            //$error_arr = validateRules($validator->errors(), $rules);

            return response()->json(['status'=>400,'errors' => $validator->errors()->all()]);
        }
        if($request->api_token != ''):
            $user = User::where('api_token',$request->api_token)->first();
        else:
            $user = null;
        endif;
        if($user):
            // $basket = Basket::where('device',$request->playerId)->orWhere('user_id',$user->id)->where('is_ordered',0)->first();
            $basket = Basket::where('user_id',$user->id)->where('is_ordered',0)->first();
            $userId = $user->id;
            $device = '';
        else:
            $basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();
            $userId = null;
            $device = $request->playerId;
        endif;

        if(! $basket):
            $basket = new Basket();
            $basket->device = $device;
            $basket->user_id = $userId;
            $basket->is_ordered = 0 ;
            $basket->save();
        endif;

        
        $model = new Item;
        $model->itemable_id = $request->item_id;
        if($request->type == 'offer'):
            $model->itemable_type = 'App\Offer';
            $model->type = 'offer';
            $offer = Offer::find($request->item_id);
            $model->item_price = $offer ? $offer->price : '';
        else:
            $model->itemable_type = 'App\Product';
            $model->type = 'product';
            $product = Product::find($request->item_id);
            $model->item_price = $product ? $product->price : ''; 
        endif;
        $model->amount = $request->amount;
        $model->basket_id = $basket->id;
        $model->save();
               

        return response()->json([
            'status' => 200,
            'message' => 'تم اضافة المنتجات للسلة بنجاح'
        ]);

    }

    public function updateBasket(Request $request)
    {
        $rules = [
            'amount' => 'required',
            'itemId' => 'required',
            'playerId' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            //$error_arr = validateRules($validator->errors(), $rules);

            return response()->json(['status'=>400,'errors' => $validator->errors()->all()]);
        }

        //$model = Item::find($request->itemId);

        //$user = auth()->user();
        if($request->api_token != ''):
            $user = User::where('api_token',$request->api_token)->first();
        else:
            $user = null;
        endif;

        if($user):
            $basket = Basket::where('user_id',$user->id)->where('is_ordered',0)->first();
        else:
            $basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();
        endif;

        if(!$basket){
            return response()->json([
                'status' => 400,
                'message' => 'عنصر غير موجود' ,
                'errors' => ['عنصر غير موجود'] ,
            ]);
        }

        $model = Item::where('id',$request->itemId)->where('basket_id',$basket->id)->first();


        if(!$model):
            return response()->json([
                'status' => 400,
                'message' => 'العنصر غير موجود' ,
                'errors' => ['عنصر غير موجود'] ,
            ]);
        endif;

        $model->amount = $request->amount;
        $model->save();
               

        return response()->json([
            'status' => 200,
            'message' => 'تم تعديل المنتجات للسلة بنجاح'
        ]);

    }

    public function deleteItem(Request $request)
    {
        $rules = [
            'itemId' => 'required',
            'playerId' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_arr = validateRules($validator->errors(), $rules);
            return response()->json(['status'=>400,'errors' => $validator->errors()->all() , 'message'=>'يرجى ادخال بيانات صحيحة']);
        }

        //$user = auth()->user();
        if($request->api_token != ''):
            $user = User::where('api_token',$request->api_token)->first();
        else:
            $user = null;
        endif;

        if($user):
            $basket = Basket::where('user_id',$user->id)->where('is_ordered',0)->first();
        else:
            $basket = Basket::where('device',$request->playerId)->where('is_ordered',0)->first();
        endif;

        if(!$basket){
            return response()->json([
                'status' => 400,
                'message' => 'عنصر غير موجود' ,
                'errors' => ['عنصر غير موجود'] ,
            ]);
        }

        $model = Item::where('id',$request->itemId)->where('basket_id',$basket->id)->first();

        if(!$model):
            return response()->json([
                'status' => 400,
                'message' => 'عنصر غير موجود' ,
                'errors' => ['عنصر غير موجود'] ,
            ]);
        endif;

        $model->delete();
   
        return response()->json([
            'status' => 200,
        ]);

    }

}
