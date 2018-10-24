<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Order;
use App\Category;
use App\Product;
use App\Offer;
use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use DateTime ;
use App\Libraries\PushNotification;
use App\Notification;
use App\UserAddress;
use App\Item;
use App\Coupon;
use App\Device;


class OrderController extends Controller
{
    
    public $push;

    public function __construct(PushNotification $push)
    {
 
        $this->push = $push;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // if (!Gate::allows('orders_manage') || !Gate::allows('reports_manage')) {
        //     return abort(401);
        // }

        //`user_id`, `basket_id`, `coupon_id`, `total_price`, `discount`, `order_date`, `order_time`, `address_id`, `status`, `user_deliverd_time`
        
        $orders = Order::join('baskets','orders.basket_id','baskets.id')->join('users','orders.user_id','users.id')->join('user_addresses','orders.address_id','user_addresses.id')->select('orders.*','baskets.id as basket_id' ,'users.id as user_id' , 'users.name as user_name' , 'user_addresses.address as user_address', 'user_addresses.city as user_city')->orderBy('id','Desc')->get();

        $orders->map(function ($q) {

            if($q->coupon_id != null):
                $coupon = Coupon::find($q->coupon_id);
                $q->couponCode= $coupon ? $coupon->code : '';
            endif;
            $address = UserAddress::find($q->address_id);
            $q->address= $address ? $address->address : '';    
            $city = City::find($q->user_city); 
            $q->city= $city ? $city->name : ''; 

            $items = Item::where('basket_id',$q->basket_id)->select('id','itemable_id','amount','item_price','itemable_type')->get();
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

        return view('admin.orders.index' , compact('orders' , 'categories' , 'cards'));
    }

    public function search(Request $request)
    {
        // if (!Gate::allows('orders_manage')) {
        //     return abort(401);
        // }

        //return $request;

        $orders = [] ;

        
        if ($request->from != '' && $request->to != '' && $request->status == -1) {
            if($request->from > $request->to){

                //return back()->with('error','يرجى ادخال فترة زمنية صحيحة');
                $type = 'search';
                $orders = [];
                $order_type = $request->order_type;
                session()->flash('error', 'يرجى ادخال فترة زمنية صحيحة');
                return view('admin.orders.index' , compact('orders' , 'type' , 'order_type'));
            }
            $orders = Order::join('baskets','orders.basket_id','baskets.id')->join('users','orders.user_id','users.id')->join('user_addresses','orders.address_id','user_addresses.id')->whereDate('orders.created_at','>=',$request->from)->whereDate('orders.created_at','<=',$request->to)->select('orders.*','baskets.id as basket_id' ,'users.id as user_id' , 'users.name as user_name' , 'user_addresses.address as user_address', 'user_addresses.city as user_city')->orderBy('id','Desc')->get();

            $orders->map(function ($q) {

                if($q->coupon_id != null):
                    $coupon = Coupon::find($q->coupon_id);
                    $q->couponCode= $coupon ? $coupon->code : '';
                endif;
                $address = UserAddress::find($q->address_id);
                $q->address= $address ? $address->address : ''; 
                $city = City::find($q->user_city); 
                $q->city= $city ? $city->name : '';   

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
            });

        }elseif ($request->from != '' && $request->to != '' && $request->status != -1) {
            if($request->from > $request->to){
                return back()->with('error','يرجى ادخال فترة زمنية صحيحة');
            }
            $orders = Order::join('baskets','orders.basket_id','baskets.id')->join('users','orders.user_id','users.id')->join('user_addresses','orders.address_id','user_addresses.id')->whereDate('orders.created_at','>=',$request->from)->whereDate('orders.created_at','<=',$request->to)->where('orders.status',$request->status)->select('orders.*','baskets.id as basket_id' ,'users.id as user_id' , 'users.name as user_name' , 'user_addresses.address as user_address', 'user_addresses.city as user_city')->orderBy('id','Desc')->get();

            $orders->map(function ($q) {

                if($q->coupon_id != null):
                    $coupon = Coupon::find($q->coupon_id);
                    $q->couponCode= $coupon ? $coupon->code : '';
                endif;
                $address = UserAddress::find($q->address_id);
                $q->address= $address ? $address->address : '';
                $city = City::find($q->user_city); 
                $q->city= $city ? $city->name : '';       

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
            });

        }elseif ($request->from == '' && $request->to == '' && $request->status != -1) {
            
            $orders = Order::join('baskets','orders.basket_id','baskets.id')->join('users','orders.user_id','users.id')->join('user_addresses','orders.address_id','user_addresses.id')->where('orders.status',$request->status)->select('orders.*','baskets.id as basket_id' ,'users.id as user_id' , 'users.name as user_name' , 'user_addresses.address as user_address', 'user_addresses.city as user_city')->orderBy('id','Desc')->get();

            $orders->map(function ($q) {

                if($q->coupon_id != null):
                    $coupon = Coupon::find($q->coupon_id);
                    $q->couponCode= $coupon ? $coupon->code : '';
                endif;
                $address = UserAddress::find($q->address_id);
                $q->address= $address ? $address->address : '';  
                $city = City::find($q->user_city); 
                $q->city= $city ? $city->name : '';     

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
            });

        } else{

            //return back()->with(compact('orders'))->with('error','يرجى اختيار الفترة الزمنية المراد البحث خلالها');
            $type = 'search';
            $orders = [];
            $order_type = $request->order_type;
            session()->flash('error', 'من فضلك يرجى اختيار فترة زمنية صحيحة');
            return view('admin.orders.index' , compact('orders' , 'type' , 'order_type'));

        }
        $type = 'search';
        $order_type = $request->order_type;
        return view('admin.orders.index' , compact('orders' , 'type' , 'order_type'));

    }

    public function show($id){

        // if (!Gate::allows('orders_manage')) {
        //     return abort(401);
        // }
        
        // $order = Order::find($id);
        
        $order = Order::join('baskets','orders.basket_id','baskets.id')->join('users','orders.user_id','users.id')->join('user_addresses','orders.address_id','user_addresses.id')->where('orders.id',$id)->select('orders.*','baskets.id as basket_id' ,'users.id as user_id' , 'users.name as user_name' , 'user_addresses.address as user_address', 'user_addresses.lat as lat', 'user_addresses.lng as lng', 'user_addresses.city as user_city')->first();

            if($order && $order->coupon_id != null):
                $coupon = Coupon::find($order->coupon_id);
                $order->couponCode= $coupon ? $coupon->code : '';
                $order->discountRatio= $coupon ? $coupon->ratio : '';
            endif;

            if($order){
                $address = UserAddress::find($order->address_id);
                $order->address= $address ? $address->address : '';  
                $city = City::find($order->user_city); 
                $order->city= $city ? $city->name : '';   
            
                $items = Item::where('basket_id',$order->basket_id)->select('id','itemable_id','amount','item_price','itemable_type')->get();
                $items->map(function ($q) {
                    $product = Product::find($q->itemable_id);
                    $offer = Offer::find($q->itemable_id);
                    if($q->itemable_type == 'App\Product'):
                        $q->type = 'product';
                        $q->cat_id = $product->category_id;
                        $q->product_name = $product->name;
                        $q->product_price = $product->price;
                        $q->product_image= Request()->root() . '/files/products/' . $product->image ;
                        //$q->offer_name = null;
                        $q->offer_price = null;
                    else:
                        $q->type = 'offer';

                        $offer_product = Product::find($offer->product_id);
                        $q->cat_id = $offer_product->category_id;
                        //$q->offer_name = $offer->name;
                        $q->offer_price = $offer->price;
                        $q->product_name = $offer_product->name;
                        $q->product_price = $offer_product->price;
                        $q->product_image= Request()->root() . '/files/products/' . $offer_product->image ;

                    endif;
                });

                $order->items = $items; 
            }
        
        return view('admin.orders.show' , compact('order'));
    }

    
    public function confirmOrder(Request $request){

        // if (!Gate::allows('orders_manage')) {
        //     return abort(401);
        // }

        $order = Order::find($request->orderId);
        

        if ($request->status == '') {
            return response()->json([
                'status' => false,
                'message' => 'من فضلك قم باختيار حالة الطلب   ',
            ]);
        }

        if ($request->status == 2 && $request->refuse_reason == '') {
            return response()->json([
                'status' => false,
                'message' => 'من فضلك قم بكتابة سبب الرفض   ',
            ]);
        }

        if ($order) {
            
            $order->status = $request->status ;
        
            if($request ->status == 2){
                $order->refuse_reason = $request->refuse_reason ;
                $body = 'تم رفض الطلب رقم : '.$order->id.' وسبب الرفض هو : '.$request->refuse_reason;
                
            }else{
                //$body = ' تم قبول الطلب';
                $body = 'تم قبول الطلب رقم : '.$order->id.' وسيتم تسلمها يوم '.$order->delivered_time;


            }

            if($order->status == 1):
                $order_status = 'جارى التجهيز';
                $body = 'جارى تجهيز الطلب  رقم : '.$order->id;
            elseif($order->status == 2):
                $order_status = 'مرفوض';
                $body = 'تم رفض الطلب رقم : '.$order->id.' وسبب الرفض هو : '.$request->refuse_reason;

            elseif($order->status == 3):
                $order_status = 'جارى التوصيل';
                $body = 'جارى توصيل الطلب  رقم : '.$order->id;

            else:
                $order_status = 'جديد';
                $body = 'طلبكم قيد الانتظار';

            endif;

            if($order->status != 0){
                $title = 'الرد على الطلب';

                $data = ['title' => $title , 'body'=>$body ,'targetType' => 'order' , 'targetId' =>$order->id];
                $devices = Device::where('user_id',$order->user_id)->pluck('device')->toArray();
                $this->push->sendPushNotification($devices , $data, $title, $body,'multi');


                $notif_data = array(
                    'user_id' => $order->user_id,
                    'user_ids' => null,
                    'push_type' => 'single',
                    'target_id' => $order->id,
                    'target_type' => 'order',
                    'title' => $title,
                    'body' => $body,
                    'image' => null,
                    'is_read' => 0,
                    'data' => json_encode($data),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );

                Notification::insert($notif_data);
            }
            
            
            if ($order->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'تم الحفظ',
                    'id' => $order->id,
                    'order_status' => $order_status,
                   
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Fail',
            ]);
        }
    }

    public function delete(Request $request)
    {
        // if (!Gate::allows('orders_manage')) {
        //     return abort(401);
        // }

        $model = Order::findOrFail($request->id);

        // if ($model->companies->count() > 0) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => "عفواً, لا يمكنك حذف النوع ($model->name) نظراً لوجود مراكز ملتحقة بهذا النوع"
        //     ]);
        // }

        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }

    public function getExport(Request $request){
        
        \Excel::create('الطلبات', function($excel) use($request){

        $excel->sheet('Sheet 1', function($sheet) use($request){

            $query=\DB::table('orders')->join('baskets','orders.basket_id','baskets.id')->join('users','orders.user_id','users.id')->join('user_addresses','orders.address_id','user_addresses.id')->select();

            if ($request->from != '' && $request->to != '') :
                $query->whereDate('orders.created_at','>=',$request->from)->whereDate('orders.created_at','<=',$request->to);
            endif;

            if ($request->status != -1) :
                $query->where('orders.status', $request->status);
            endif;

            $orders = $query->select('orders.*','baskets.id as basket_id' ,'users.id as user_id' , 'users.name as user_name' , 'user_addresses.address as user_address')->orderBy('id','Desc')->get();

            $orders->map(function ($q) {
                 
                if($q->status == 0):
                    $status = 'جديد';
                elseif($q->status ==1):
                    $status = 'جارى التجهيز';
                elseif($q->status ==2):
                    $status = 'مرفوض';
                elseif($q->status ==3):
                    $status = 'جارى التوصيل';
                else:
                    $status = 'مكتمل';
                endif;

                $q->order_status = $status;
                 
             });

           // Add heading row
       
        $data[] = array('وقت وتاريخ الطلب', 'رقم الطلب', 'اسم المستخدم', 'حالة الطلب');
        $sheet->fromArray(array($data), null, 'A1', false, false);

        // Add data rows
                foreach($orders as $order) {
                 $data[] = array(
                    $order->created_at,
                    $order->id,
                    $order->user_name ,
                    $order->order_status ,                    
                );
            }
            $sheet->fromArray($data, null, 'A1', false, false);
        });
    })->download('xls');
   
    }
        
}
