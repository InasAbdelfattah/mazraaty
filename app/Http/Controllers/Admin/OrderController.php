<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Order;
use App\Category;
use App\UserCard;
use App\Card;
use App\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use DateTime ;

use App\Notification;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;


class OrderController extends Controller
{
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

        //`doc_photo`, `username`, `phone`, `phone2`, `delivered_time`, `address`, `card_id`, `user_id`, `payment_method`, `status`, `refuse_reason`
        $categories = Category::where('status',1)->select('id' , 'name_ar as name')->get();
        
        $cards = Card::where('status',1)->select('id' , 'name_ar as name')->get();
        
        $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->orderBy('id','Desc')->get();

        return view('admin.orders.index' , compact('orders' , 'categories' , 'cards'));
    }

    public function search(Request $request)
    {
        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }

        $orders = [] ;
        $categories = Category::where('status',1)->select('id' , 'name_ar as name')->get();
        
        $cards = Card::where('status',1)->select('id' , 'name_ar as name')->get();
        
        if ($request->status != '' && $request->card_id == '') {
            
            //$cardType = Category::where('name_ar','like','%'.$request->service_type.'%')->first();

                
            $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->where('orders.status',$request->status)->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->get();

        }elseif ($request->status == '' && $request->card_id != '') {
            //$user = User::where('name','like','%'.$request->service_provider.'%')->first();
            
            $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->where('orders.card_id',$request->card_id)->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->get();

        }elseif ($request->status != '' && $request->card_id != '') {
            
            $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->where('orders.card_id',$request->card_id)->where('orders.status',$request->status)->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->get();
        }elseif ($request->from != '' && $request->to != '' && $request->status != '' && $request->card_id != '') {
            
            $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->where('orders.card_id',$request->card_id)->where('orders.status',$request->status)->whereDate('orders.created_at','>',$request->from)->whereDate('orders.created_at','<',$request->to)->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->get();
        }elseif ($request->from != '' && $request->to != '' && $request->card_type == '' && $request->card_id == '') {
            
            $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->whereDate('orders.created_at','>',$request->from)->whereDate('orders.created_at','<',$request->to)->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->get();
        }elseif ($request->from != '' && $request->to != '' && $request->status != '' && $request->card_id == '') {
            
            $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->where('orders.status',$request->status)->whereDate('orders.created_at','>',$request->from)->whereDate('orders.created_at','<',$request->to)->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->get();
        }elseif ($request->from != '' && $request->to != '' && $request->status == '' && $request->card_id != '') {
            
            $orders = Order::join('cards','orders.card_id','cards.id')->join('users','orders.user_id','users.id')->where('orders.card_id',$request->card_id)->whereDate('orders.created_at','>',$request->from)->whereDate('orders.created_at','<',$request->to)->select('orders.*','cards.id as card_id' , 'cards.name_ar as card_name' ,'users.id as user_id' , 'users.name as user_name')->get();
        }
        else{

            return back()->with(compact('orders'))->with('fail','من فضلك يرجى كتابة اسم مزود الخدمة أو اختيار نوع الخدمة');

        }
        $type = 'search';
        return view('admin.orders.index' , compact('orders' , 'type' , 'categories' , 'cards'));

    }

    public function show($id){

        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }
        
        $order = Order::find($id);
        
        if($order):
            $card = Card::find($order->card_id);
            $payment = Payment::where('order_id',$order->id)->first();
            if($card):
                $type = Category::find($card->category_id);
            else:
                $type = null ;
            endif;
        else:
            $card = null;
            $type = null ;
            $payment = null;
        endif;
        
        return view('admin.orders.show' , compact('order','card','type','payment'));
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
            
            if($order->status == 0){
                $delivered_time = $order->delivered_time;
            }elseif($order->status == 3){
                $userCard = UserCard::where('card_id',$order->card_id)->where('user_id',$order->user_id)->first();
                if($userCard):
                    $delivered_time = $userCard->to;
                endif;
            }

            $order->status = $request->status ;
            $card = Card::where('id',$order->card_id)->first();
            if($request->status == 1){
                
                if($card){
                    $effectiveDate = $delivered_time;
                    $expiration = intval($card->expiration);
                    $user_card = new UserCard();
                    $user_card->user_id = $order->user_id;
                    $user_card->card_id = $order->card_id;
                    // $myDateTime = DateTime::createFromFormat('Y-m-d', $order->delivered_time);
                    // $user_card->from = $myDateTime->format('d-m-Y');
                    $user_card->from = $delivered_time;
                    $effectiveDate = date('Y-m-d', strtotime("+".$expiration." months", strtotime($effectiveDate)));
                    $user_card->to = $effectiveDate ;
                    $user_card->save();

                }
            }
            
        
            if($request ->status == 2){
                $order->refuse_reason = $request->refuse_reason ;
                $body = 'تم رفض الطلب وسبب الرفض هو : '.$request->refuse_reason;
                
            }else{
                //$body = ' تم قبول الطلب';
                $body = 'تم قبول طلب شراء البطاقة وسيتم تسلمها يوم '.$delivered_time;
            }
            
            if($card):
                $title = $card->name_ar;
            else:
                $title = 'الرد على طلب بطاقة';
            endif;
            
            
            
            $notif = sendOneSignalNotif('single' ,$order->user_id , $title , $body ,$image=null , 'order');
            
            //dd($notif);
            
            if ($order->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'تم الحفظ',
                    'id' => $order->id,
                    'order_status' => $order->status == 1 ? 'سارى' : 'مرفوض',
                   
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

    public function getExport(){
        \Excel::create('التقارير المالية', function($excel) {

        $excel->sheet('Sheet 1', function($sheet) {


            $orders=\DB::table('orders')->get();

            $orders->map(function ($q) {
                 $q->user= User::find($q->user_id) ? User::find($q->user_id)->name : '';
                 
                if($q->payment_method  == 0):
                     $method = 'تحويل بنكى';
                elseif($q->payment_method  == 1):
                    $method = 'بطاقة ائتمان';
                else :
                    $method = 'دفع عند الاستلام';    
                endif;
                    
                 $q->payment_method= $method ;
                 $q->payment_status= $q->payment_status  == 1 ? 'سدد' : 'لم يسدد';
                 
                 
             });

           // Add heading row
       
        $data[] = array('الهاتف', 'اسم المستخدم', 'الرمز الترويجى', 'كود الموظف','وسيلة الدفع','حالة السداد');
        $sheet->fromArray(array($data), null, 'A1', false, false);


        // Add data rows
                foreach($orders as $order) {
                 $data[] = array(
                    $order->phone,
                    $order->user,
                    $order->promo_code ,
                    $order->emp_code ,
                    $order->payment_method,
                    $order->payment_status,
                    
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
    
    
     private function sendSingleNotification($title , $msg , $user_id ,$notif_type){

        $device = \App\Device::where('user_id',$user_id)->first();
        if($device):
            $token = $device->device;
        else:
            $token = '';
        endif;

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $notificationBuilder = new PayloadNotificationBuilder('طلتك');
        $notificationBuilder->setBody($msg)
            ->setSound('default');
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        $notif = new Notification();
        $notif->msg = $msg;
        $notif->title = $title;
        $notif->image = '';
        $notif->to_user = $user_id;
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
            
            return true;
        }
        
        return false;
    }

}
