<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Device;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;


class NotificationController extends Controller {

    public function getIndex(){
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }
       

        // $data = Device::join('users','devices.user_id','users.id')->select('devices.*','users.id as user_id', 'users.name as username')->get();
        
        $notifs = Notification::where('type','لوحة التحكم')->get();
        return view('admin.notifs.notifs', compact('notifs'));
        //return view('admin.notifs.all', compact('data'));
    }

    public function getNotif() {

        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        $data = Device::join('users','devices.user_id','users.id')->select('devices.*','users.id as user_id', 'users.name as username')->get();

        $users = User::all();

        return view('admin.notifs.index', compact('data' , 'users'));
    }
    
    public function send(Request $request){
        
        $rules = [
            'msg' => 'required',
            'device_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->route('new-notif')->withErrors($validator)->withInput();
        }
        
        if($request->device_id == 'all'){
            $notif_type = 'all';
            $user_id=null ;
        }else{
            $notif_type = 'single';
            $user_id = $request->device_id;
        }
        
        sendOneSignalNotif($notif_type , $user_id , $request->title , $request->msg ,$image=null , 'لوحة التحكم');
        
        return redirect()->route('new-notif')->with('success', 'تم الارسال بنجاح');
    }
    
    // public function notifs(){
        
    // }
    
    public function show($id){
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }
        $data = Notification::find($id);
        return view('admin.notifs.details',compact('data'));
    }
    
    public function delete(Request $request){
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        $model = Notification::findOrFail($request->id);

        
        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }

    public function send2(Request $request) {

        if (!Gate::allows('notifications_manage')) {
            return abort(401);
        }
        
        $rules = [
            'msg' => 'required',
            'device_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->route('new-notif')->withErrors($validator)->withInput();
        }

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('طلتك');
        $notificationBuilder->setBody($request->msg)
                            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        //$token = "a_registration_from_your_database";

        
        if($request->device_id == 'single'){
            //case:push notif to one device
            $token = $request->device_id;
            $type = 'single';

        }elseif($request->device_id == 'providers'){
            //case:you push notif to providers
            $users = User::where('is_user',1)->pluck('id')->toArray();
            $token = [];
            if(count($users) > 0){
                $token = Device::whereIn('user_id',$users)->pluck('device')->toArray();
            }
            if(count($token) == 0){
                $token = '';
            }
            $type = 'providers';
        }elseif($request->device_id == 'users'){
            //case:you push notif to providers
            $users = User::where('is_user',1)->pluck('id')->toArray();
            $token = [];
            if(count($users) > 0){
                $token = Device::whereIn('user_id',$users)->pluck('device')->toArray();
            }
            if(count($token) == 0){
                $token = '';
            }
            $type = 'users';
        }else{
            //case:you push notif to all user
            $token = Device::where('device','!=','')->pluck('device')->toArray();
            if(count($token) == 0){
                $token = '';
            }
            $type = 'all';
            //dd($token);
        }
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

            $notif = new Notification();
            $notif->msg = $request->msg;
            $notif->title = $request->title;
            $notif->image = '';
            $tokens = [];
            if($type == 'all'){
                foreach($token as $user_token){
                    $user_id = Device::where('device',$user_token)->first();
                    if($user_id){
                        $user = User::find($user_id->user_id);
                        if($user){
                            array_push($tokens,$user->id);
                        }  
                    }
                }
                $notif->to_user = json_encode($tokens);
            }elseif($type == 'providers'){
                //dd($token);
                foreach($token as $user_token){
                    $user_id = Device::where('device',$user_token)->first();
                    if($user_id){
                        $user = User::find($user_id->user_id);
                        if($user){
                            array_push($tokens,$user->id);
                        }
                    }
                }
                $notif->to_user = json_encode($tokens);
            }elseif($type == 'users'){
                foreach($token as $user_token){
                    $user_id = Device::where('device',$user_token)->first();
                    if($user_id){
                        $user = User::find($user_id->user_id);
                        if($user){
                            array_push($tokens,$user->id);
                        }
                    }
                }
                $notif->to_user = json_encode($tokens);
            }else{
                dd($type);
//                //$user = User::where('device_id',$token)->first();
                $user_id = Device::where('device',$request->device_id)->first();
                $notif->to_user = $user_id->user_id;
            }
            $notif->type = $type;
            $notif->save();
            return redirect()->route('new-notif')->with('success', 'تم الارسال بنجاح');
        }else{
            return redirect()->route('new-notif')->with('fail', 'لم يتم الارسال');
        }
    }

}