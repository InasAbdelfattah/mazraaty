<?php

namespace App\Http\Controllers\Admin;

use App\Support;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Sms;

class SupportsController extends Controller
{
    public function index()
    {
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        $supports = Support::whereParentId(0)->orderBy('id','desc')->get();
        return view('admin.supports.index')->with(compact('supports'));
    }

    public function show($id)
    {

        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        //$message = Support::with('user')->whereId($id)->first();
        $message = Support::whereId($id)->first();
        $user = User::where('id',$message->user_id)->first();
        $message->is_read = 1;
        $message->save();

        return view('admin.supports.show')->with(compact('message','user'));
    }

    public function reply(Request $request, $id)
    {
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        if ($request->message == '' && $request->reply_type == '') {
            return response()->json([
                'status' => false,
                'message' => 'من فضلك ادخل بيانات الرسالة ثم اعد الإرسال'
            ]);
        }


        if ($request->message == '') {
            return response()->json([
                'status' => false,
                'message' => 'من فضلك ادخل نص الرد '
            ]);
        }


        if ($request->reply_type == '') {
            return response()->json([
                'status' => false,
                'message' => 'من فضلك اختار وسيلة الرد '

            ]);
        }

        $support = new Support;
        //$support->title = 'رد على الرسالة';
        $support->message = $request->message;
        //$support->phone = $request->phone ? $request->phone : '';
        $support->title = $request->name ? $request->name : '';
        $support->user_id = auth()->id();
        $support->type = -1;

        $support->reply_type = $request->reply_type;

        $support->parent_id = $id ;
        $support->is_read = 0;

        if ($support->save()) {
            
            $support->created = $support->created_at->format(' Y/m/d  ||  H:i:s ');
            $msg = Support::find($support->parent_id);
            Sms::sendActivationCode($support->message , $msg->phone);
            
             $title = 'رد على الرسالة';
             $body = $support->message;
             
             $old_msg = Support::find($id);
            if($old_msg):
                sendOneSignalNotif('single' ,$old_msg->user_id , $title , $body ,$image=null , 'contact');
            endif;
            return response()->json([
                'status' => true,
                'message' => 'لقد تم إرسال الرد بنجاح',
                'data' => $support

            ], 200);
        } else {
            return response()->json([
                'status' => false,
            ]);
        }
    }

    public function delete(Request $request)
    {
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }
        
        $model = Support::findOrFail($request->id);


        if ($model->children->count() > 0) {
            
            foreach($model->children as $child){
                $child->delete();
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
