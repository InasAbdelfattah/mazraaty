<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Order;
use App\Category;
use App\Product;
use App\Offer;
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

        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }

        //`user_id`, `basket_id`, `coupon_id`, `total_price`, `discount`, `order_date`, `order_time`, `address_id`, `status`, `user_deliverd_time`
        
        $orders = Order::join('baskets','orders.basket_id','baskets.id')->join('users','orders.user_id','users.id')->join('user_addresses','orders.address_id','user_addresses.id')->select('orders.*','baskets.id as basket_id' ,'users.id as user_id' , 'users.name as user_name' , 'user_addresses.address as user_address', 'user_addresses.city as user_city')->orderBy('id','Desc')->get();

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

        return view('admin.orders.index' , compact('orders' , 'categories' , 'cards'));
    }

    public function search(Request $request)
    {
        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }
        //return $request;

        $orders = [] ;

        
        if ($request->from != '' && $request->to != '' && $request->status == '') {
            if($request->from > $request->to){
                return back()->with('error','يرجى ادخال فترة زمنية صحيحة');
            }
            $orders = Order::join('baskets','orders.basket_id','baskets.id')->join('users','orders.user_id','users.id')->join('user_addresses','orders.address_id','user_addresses.id')->whereDate('orders.created_at','>',$request->from)->whereDate('orders.created_at','<',$request->to)->select('orders.*','baskets.id as basket_id' ,'users.id as user_id' , 'users.name as user_name' , 'user_addresses.address as user_address', 'user_addresses.city as user_city')->orderBy('id','Desc')->get();

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
            });

        }elseif ($request->from != '' && $request->to != '' && $request->status != '') {
            if($request->from > $request->to){
                return back()->with('error','يرجى ادخال فترة زمنية صحيحة');
            }
            $orders = Order::join('baskets','orders.basket_id','baskets.id')->join('users','orders.user_id','users.id')->join('user_addresses','orders.address_id','user_addresses.id')->whereDate('orders.created_at','>',$request->from)->whereDate('orders.created_at','<',$request->to)->where('orders.status',$request->status)->select('orders.*','baskets.id as basket_id' ,'users.id as user_id' , 'users.name as user_name' , 'user_addresses.address as user_address', 'user_addresses.city as user_city')->orderBy('id','Desc')->get();

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
            });

        }elseif ($request->from == '' && $request->to == '' && $request->status != '') {
            
            $orders = Order::join('baskets','orders.basket_id','baskets.id')->join('users','orders.user_id','users.id')->join('user_addresses','orders.address_id','user_addresses.id')->where('orders.status',$request->status)->select('orders.*','baskets.id as basket_id' ,'users.id as user_id' , 'users.name as user_name' , 'user_addresses.address as user_address', 'user_addresses.city as user_city')->orderBy('id','Desc')->get();

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
            });

        } else{

            return back()->with(compact('orders'))->with('error','يرجى اختيار الفترة الزمنية المراد البحث خلالها');

        }
        $type = 'search';
        $order_type = $request->order_type;
        return view('admin.orders.index' , compact('orders' , 'type' , 'order_type'));

    }

    public function show($id){

        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }
        
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
            
                $items = Item::where('basket_id',$order->basket_id)->select('id','itemable_id','amount','itemable_type')->get();
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

    public function getFinancialReports()
    {
        //status = 3 when order is finished
        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }

        $users = User::where('is_user',1)->where('is_active',1)->select('id' , 'name')->get();
        $categories = Category::where('status',1)->select('id' , 'name_ar as name')->get();
        $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->orderBy('updated_at','Desc')->get();

        return view('admin.orders.reports' , compact('orders' , 'categories' , 'users'));
    }
    
    public function searchFinancialReports2(Request $request)
    {
        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }
        $categories = Category::where('status',1)->select('id' , 'name_ar as name')->get();
        $users = User::where('is_user',1)->where('is_active',1)->select('id' , 'name')->get();

        $orders = [] ;
        //dd($request);
        if($request->from != '' && $request->to != ''){
            if($request->from < $request->to){

                $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->whereDate('orders.created_at','>',$request->from)->whereDate('orders.created_at','<',$request->to)->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->get();
            }else{
                //return 'fail';
                return back()->with('error','يرجى ادخال فترة زمنية صحيحة');
            }
        }elseif ($request->card_type != '' && $request->user == '') {
            
            $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->where('cards.category_id',$request->card_type)->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->get();

        }elseif ($request->card_type == '' && $request->user != '') {
            
            //$user = User::where('name','like','%'.$request->user.'%')->first();
            
            $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->where('orders.user_id',$request->user)->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->get();

        }elseif ($request->card_type != '' && $request->user != '') {
            
            //$user = User::where('name','like',$request->user)->first();
            //$cardType = Category::where('name_ar','like',$request->card_type)->first();
             $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->where('cards.category_id',$request->card_type)->where('cards.user_id',$request->user)->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->get();
        }else{

            return back()->with(compact('orders'))->with('error','من فضلك يرجى المستخدم أو فئة البطاقة');
        }

        $type = 'search';
        
        return view('admin.orders.reports' , compact('orders' , 'users' , 'categories' , 'type'));

    }

    public function searchFinancialReports(Request $request)
    {
        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }
        $categories = Category::where('status',1)->select('id' , 'name_ar as name')->get();
        $users = User::where('is_user',1)->where('is_active',1)->select('id' , 'name')->get();

        if($request->card_type == '' && $request->user == '' && $request->from == '' && $request->to == '' && $request->payment_method == '' && $request->payment_status == ''):
            return back()->with('error','يرجى اختيار حقول البحث');
        endif;

        $query = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name');

        if ($request->from != '' && $request->to != '') :
            if($request->from < $request->to){
                $query->whereDate('orders.created_at','>',$request->from)->whereDate('orders.created_at','<',$request->to);
            }else{
                return back()->with('error','يرجى ادخال فترة زمنية صحيحة');
            }
        endif;

        if ($request->card_type && $request->card_type != '') :
            $query->where('cards.category_id',$request->card_type);
        endif;

        if ($request->user != '') :
            $query->where('orders.user_id',$request->user);
        endif;

        if ($request->payment_method != '') :
            $query->where('orders.payment_method',$request->payment_method);
        endif;

        if ($request->payment_status != '') :
            $query->where('orders.payment_status',$request->payment_status);
        endif;

        $orders = $query->get();

        $type = 'search';
        
        return view('admin.orders.reports' , compact('orders' , 'users' , 'categories' , 'type'));

    }
    
     public function getUsersReports(){
        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }
        
        //$users = User::where('is_user',1)->select('users.id' , 'users.name','users.phone','users.address')->get();
        $users = User::join('user_cards','users.id','user_cards.user_id')->where('users.is_active',1)->groupBy('users.id')->select('users.id' , 'users.name','users.phone','users.address')->get();

    
        return view('admin.orders.user_reports' , compact('users'));
     }
     
    
    public function searchUsersReports(Request $request){
        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }
        
        // $users = DB::table('users')
        //     ->leftJoin('user_cards', 'users.id', '=', 'user_cards.user_id')
        //     ->select('users.id' , 'users.name','users.phone','users.address')->get();
            
             //->whereNull('orders.customer_id')
        
        if($request->type == ''):
            return back()->with('error','يرجى اختيار نوع البحث');
        endif;

        if ($request->type == 0) :
            //users bought card
        $users = User::join('user_cards','users.id','user_cards.user_id')->where('users.is_active',1)->groupBy('users.id')->select('users.id' , 'users.name','users.phone','users.address')->get();
        endif;
        
        if ($request->type == 1) :
            //users didn't bought card
        $users = User::join('user_cards','users.id','!=','user_cards.user_id')->where('users.is_active',1)->groupBy('users.id')->select('users.id' , 'users.name','users.phone','users.address')->get();
        endif;
        
        if ($request->type == 2) :
            //users didn't complete order buying card
        $users = User::join('orders','users.id','orders.user_id')->where('users.is_active',1)->where('orders.status','!=',1)->groupBy('users.id')->select('users.id' , 'users.name','users.phone','users.address')->get();
        endif;

        $type = 'search';
        
        return view('admin.orders.user_reports' , compact('users' ,'type'));
    }

    
    public function confirmOrder(Request $request){

        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }

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
                $body = 'تم رفض الطلب وسبب الرفض هو : '.$request->refuse_reason;
                
            }else{
                //$body = ' تم قبول الطلب';
                $body = 'تم قبول الطلب وسيتم تسلمها يوم '.$order->delivered_time;


            }

            if($order->status == 1):
                $order_status = 'جارى التجهيز';
                $body = 'جارى تجهيز الطلب';
            elseif($order->status == 2):
                $order_status = 'مرفوض';
                $body = 'تم رفض الطلب وسبب الرفض هو : '.$request->refuse_reason;

            elseif($order->status == 3):
                $order_status = 'جارى التوصيل';
                $body = 'جارى توصيل الطلب';

            else:
                $order_status = 'جديد';
                $body = 'طلبكم قيد الانتظار';

            endif;

            if($order->status != 0){
                $title = 'الرد على الطلب';

                $data = ['title' => $title , 'body'=>$body ,'targetType' => 'order' , 'targetId' =>$order->id];

                $r = $this->push->sendPushNotification($order->user_id , $data, $title, $body,'global');


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
        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }

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

            if ($request->status) :
                $query->where('status', $request->status);
            endif;

            if ($request->from != '' && $request->to != '' && $request->from < $request->to) :
                $query->whereDate('orders.created_at','>',$request->from)->whereDate('orders.created_at','<',$request->to);
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
            /*print_r($data);
        die();*/
            //$sheet->fromArray($data); 
            $sheet->fromArray($data, null, 'A1', false, false);
        });
    })->download('xls');
   
    }
    
    
     public function getExportUsers(){
        \Excel::create('تقارير المستخدمين', function($excel) {

        $excel->sheet('Sheet 1', function($sheet) {


            //$users=\DB::table('users')->get();
            $users = User::join('user_cards','users.id','user_cards.user_id')->where('users.is_active',1)->groupBy('users.id')->select('users.id' , 'users.name','users.phone','users.address')->get();
            
           // Add heading row
       
       
        $data[] = array('رقم الجوال' , 'اسم المستخدم' , 'العنوان');
        $sheet->fromArray(array($data), null, 'A1', false, false);


        // Add data rows
                foreach($users as $user) {
                 $data[] = array(
                    $user->phone,
                    $user->name,
                    $user->address ,
                    
                    
                );
            }
            /*print_r($data);
        die();*/
            //$sheet->fromArray($data); 
            $sheet->fromArray($data, null, 'A1', false, false);
        });
    })->download('xls');
   
    }
    
}
