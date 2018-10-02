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
        $pageSize = $request->get('pageSize', 15); // Default to 15
        $skipCount = $request->get('skipCount', 0); // Default to 0

        $user = auth()->user();

        // `user_id`, `user_ids`, `title`, `body`, `target_id`, `target_type`, `push_type`, `image`, `is_read`, `created_at`, `updated_at`, `data`


        $query = Notification::where('user_id',$user->id)->orderBy('created_at', 'desc')
            ->select();

        $query->skip($skipCount);
        $query->take($pageSize);

        $notifications = $query->select('id','title','body','target_id','target_type','created_at' ,'is_read')->get();
        
        return response()->json([
            'status' => 200,
            'data' => $notifications,
        ]);

    }

    public function countNotifs(Request $request){

        $user = auth()->user();
        $notifs_count = Notification::where('user_id',$user->id)->where('is_read',0)->count();
        
        return response()->json([
                'status' => 200,
                'count' => $notifs_count
            ]);
    }


    public function delete(Request $request)
    {

        $user = auth()->user();

        $is_deleted = Notification::where('id',$request->notifId)->where('user_id',$user->id)->first();
        

        if ($is_deleted) {
            
            $is_deleted->delete();

            $notifs_count = Notification::where('user_id',$user->id)->where('is_read',0)->count();

            return response()->json([
                'status' => 200,
                'count' => $notifs_count
            ]);
        } else {
            return response()->json([
                'status' => 400,
            ]);
        }
    }
}
