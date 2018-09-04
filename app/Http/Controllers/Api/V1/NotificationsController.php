<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\User;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class NotificationsController extends Controller
{
    public function __construct(){
        app()->setlocale(request('lang'));
        Carbon::setLocale(app()->getlocale());
    }
    
    public function getUserNotifications(Request $request)
    {
        /**
         * Set Default Value For Skip Count To Avoid Error In Service.
         * @ Default Value 15...
         */
        if (isset($request->pageSize)):
            $pageSize = $request->pageSize;
        else:
            $pageSize = 15;
        endif;
        /**
         * SkipCount is Number will Skip From Array
         */
        $skipCount = $request->skipCount;
        $itemId = $request->itemId;

        $currentPage = $request->get('page', 1); // Default to 1

        $user = User::whereApiToken($request->api_token)->first();
       
       
        $notifi1 = Notification::whereType('all')->get();
        
        $notifi2 = Notification::whereType('single')->where('to_user',$user->id)->get();
        
         $userType= $user->is_provider==1 ?'providers':'users';
         
        $notifi3 = Notification::whereType($userType)->get();
        
        $merged = $notifi3->merge($notifi1);
        $merged = $merged->merge($notifi2);
        
        
        $all = array_reverse(array_sort($merged, function ($value) {
            return $value['created_at'];
        }));
        
        return response()->json([
            'status' => true,
            'data' => $all,
            'inas'=>'iiiiiiii'
        ]);
       
       
       //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
       
        // $notifs = Notification::where('type','single')->where('to_user',$user->id)->orderBy('id','desc')->get();
            
        // foreach($notifs as $notif){
        //     $notif->is_seen = 1 ;
        //     $notif->save();
        // }
           
        // // $notifications = Notification::where('type','all')->orWhere('to_user',$user->id)->skip($skipCount + (($currentPage - 1) * $pageSize))
        // //     ->take($pageSize)->orderBy('id','desc')->get();
            
        // // $notification = Notification::where('type','!=','single')->where('to_user',$user->id)->orderBy('id','desc')->get();
        
        // if($user->is_provider == 1){
        //     $notification2 = Notification::where('type','providers')->where('type','all');
        // }else{
        //     $notification2 = Notification::where('type','users')->where('type','all');
        // } 
        
        
        
        
        // $first = DB::table('notifications')
        //     ->where('type','single')->where('to_user',$user->id);

        // $notificatio = DB::table('notifications')
        //     ->where('type','all')
        //     ->union($first)
        //     ->skip($skipCount + (($currentPage - 1) * $pageSize))
        //     ->take($pageSize)->orderBy('id','desc')->get();
        
            
        // //$notifications = $notification1->union($notification2)->get();
        
        // $notificatio->map(function ($q) {
            
        //     $q->since = $q->created_at;
        // });
        
        
          //-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        
       



    }


    public function getCompanyNameByID($id)
    {
        $company = Company::whereId($id)->first();
        return $company->name;
    }
    
    public function countNotifs(Request $request){
        $user = User::whereApiToken($request->api_token)->first();
        $notifs_count = Notification::where('type','single')->where('to_user',$user->id)->where('is_seen',0)->count();
        
        return response()->json([
                'status' => true,
                'count' => $notifs_count
            ]);
    }


    public function delete(Request $request)
    {

        $user = User::whereApiToken($request->api_token)->first();


//        return $user->notifications;
        //$is_deleted = $user->notifications()->where('id', $request->notifId)->delete();
        
        $is_deleted = Notification::where('id',$request->notifId)->where('type','single')->where('to_user',$user->id)->first();
        

        if ($is_deleted) {
            
            $is_deleted->delete();
            return response()->json([
                'status' => true,
                //'count' => $user->unreadNotifications->count()
            ]);
        } else {
            return response()->json([
                'status' => false,
            ]);
        }
    }
}
