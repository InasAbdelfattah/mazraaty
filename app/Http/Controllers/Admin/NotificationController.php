<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\City;
use App\Device;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use App\Libraries\PushNotification;

// use LaravelFCM\Message\OptionsBuilder;
// use LaravelFCM\Message\PayloadDataBuilder;
// use LaravelFCM\Message\PayloadNotificationBuilder;
// use FCM;



class NotificationController extends Controller {

    public $push;

    public function __construct(PushNotification $push)
    {
 
        $this->push = $push;
    }

    public function getIndex(){
        if (!Gate::allows('notifications_manage')) {
            return abort(401);
        }
    
    //`user_id`, `user_ids`, `title`, `body`, `target_id`, `target_type`, `push_type`, `image`, `is_read`, `created_at`, `updated_at`, `data`       

        // $data = Device::join('users','devices.user_id','users.id')->select('devices.*','users.id as user_id', 'users.name as username')->get();
        
        $notifs = Notification::where('push_type','global')->groupBy('created_at')->orderBy('id','DESC')->get();
        $type = 'notifs';

        $cities = City::all();
        return view('admin.notifs.notifs', compact('notifs','cities','type'));
        //return view('admin.notifs.all', compact('data'));
    }

    public function search(Request $request)
    {
        if (!Gate::allows('notifications_manage')) {
            return abort(401);
        }

        $notifs = [] ;

        $query = Notification::where('push_type','global')->select();
        // if($request->city):
        //     $query->where('push_type','cities')->where('target_id',$request->city);
        // endif;

        if($request->notif_date != ''):
            $query->whereDate('created_at',$request->notif_date);
        endif;

        $notifs = $query->orderBy('id','DESC')->get();

        $cities = City::all();
        $type='search';
        
        return view('admin.notifs.notifs', compact('notifs','cities','type'));

    }

    public function getNotif() {

        if (!Gate::allows('notifications_manage')) {
            return abort(401);
        }

        // $data = Device::join('users','devices.user_id','users.id')->select('devices.*','users.id as user_id', 'users.name as username')->get();

        // $users = User::all();
        $cities = City::where('status',1)->get();

        return view('admin.notifs.create',compact('cities'));
    }
    
    public function send(Request $request){
        
        $rules = [
            'body' => 'required',
            'push_type' => 'required'
        ];

        if($request->push_type == 'cities'){
            $rules['city'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->route('new-notif')->withErrors($validator)->withInput();
        }

        $title = config('app.name');
        $data = ['title' => $title , 'body'=>$request->body];

        //$r = $this->push->sendPushNotification(null , $data, $title, $request->body ,$request->push_type);

        $devices = Device::pluck('device')->toArray();
        //return $devices;
        $r = $this->push->sendPushNotification($devices , $data, $title, $request->body ,'multi');
        //return $r;
        
        
        $users = [];
        if($request->push_type == 'global'):
            $users = User::where('is_admin',0)->select('id')->get();
            $target_id = null;
            //$ids = $users->pluck('id');
        else:
            $users = User::where('is_admin',0)->where('city_id',$request->city)->select('id')->get();
            $target_id = $request->city;
        endif;

        if(count($users) > 0){
            $notif_data = [];
            foreach ($users as $user) {

                $notif_data[] = array(
                    'user_id' => $user->id,
                    'user_ids' => null,
                    'push_type' => $request->push_type,
                    'target_id' => $target_id,
                    'target_type' => 'notification',
                    'title' => $title,
                    'body' => $request->body,
                    'image' => null,
                    'is_read' => 0,
                    'data' => json_encode($data),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );

            }

            Notification::insert($notif_data);
        }

        //return $r;
        
        return redirect()->route('notifs')->with('success', 'تم الارسال بنجاح');
    }
    
    // public function notifs(){
        
    // }
    
    public function show($id){
        if (!Gate::allows('notifications_manage')) {
            return abort(401);
        }
        $data = Notification::find($id);
        return view('admin.notifs.details',compact('data'));
    }
    
    public function delete(Request $request){
        // if (!Gate::allows('notifications_manage')) {
        //     return abort(401);
        // }

        $model = Notification::findOrFail($request->id);
        $query = Notification::where('id','!=',$model->id)->where('created_at',$model->created_at)->get();

        if(count($query) > 0){
            foreach ($query as $q) {
                $q->delete();
            }
        }
        
        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }

}