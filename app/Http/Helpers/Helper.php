<?php

     function uploadApiImage($image, $path = null, $width = null, $height = null)
    {
        //if ($request->hasFile($name)):
            // Get File name from POST Form
            //$image = $request->file($name);

            // Custom file name with adding Timestamp
            $filename = time() . '.' . str_random(20) . $image->getClientOriginalName();

            // Directory Path Save Images
            $path = public_path($path . $filename);

            // Upload images to Target folder By INTERVENTION/IMAGE
            $img = Image::make($image);

            // RESIZE IMAGE TO CREATE THUMBNAILS
            if (isset($width) || isset($height))
                $img->resize($width, $height, function ($ratio) {
                    $ratio->aspectRatio();
                });
            $img->save($path);

            // RETURN path to save in images tables DATABASE
            return $filename;
       // endif;
    }

    function setting(){
        return new \App\Setting();
    }
    
    function countNotifs(){
        $count = 0;
        if(auth()->user()){
            $count = \App\Notification::where('notif_type','single')->where('to_user',auth()->user()->id)->where('is_seen',0)->count();
            $notifi1 = \App\Notification::where('notif_type','all')->get();
            
           $notifi1 = $notifi1->filter(function ($q) {
               if($q->hidden_from != null){
                    if(!in_array(auth()->user()->id, json_decode($q->hidden_from))):
                        return $q;
                    endif;
                    
                }else{
                    return $q;
                }
            });

            $notifi2 = \App\Notification::where('notif_type','single')->where('to_user',auth()->user()->id)->where('is_seen',0)->get();
        
            $merged = $notifi1->merge($notifi2);
        }
        
        return count($merged) ;
    }
    
    function getNotifs(){
        $count = [];
        $notifications = [];
        if(auth()->user()){
            $count = \App\Notification::where('notif_type','single')->where('to_user',auth()->user()->id)->where('is_seen',0)->get();
            
            $notifi1 = \App\Notification::where('notif_type','global')->get();
            $notifi2 = \App\Notification::where('notif_type','single')->where('to_user',auth()->user()->id)->where('is_seen',0)->get();
        
            $merged = $notifi1->merge($notifi2);

            $notifications = array_reverse(array_sort($merged, function ($value) {
                return $value['created_at'];
            }));
            
            $output = array_slice($notifications, 0 , 50);
            
            // $notifications->map(function ($q) {
            //     $q->since = $q->created_at->diffForHumans();
            // });

        }
        
        return $notifications ;
    }
    
    function getCategories($lang){
        $cats = [];
        //$cats = \App\Category::where('status',1)->get();
        $cats = \App\Category::where('status',1)->select('id','name_'.$lang.' as name','description_'.$lang.' as description' , 'image')->whereParentId(0)->get();

        return $cats ;
    }
    
    function deleteNotif($id){
        $count = [];
        if(auth()->user()){
            // $notif = \App\Notification::where('id',$id)->where('notif_type','single')->where('to_user',auth()->user()->id)->where('is_seen',0)->first();
            // if($notif){
            //     $notif->delete();
            //     return response()->json(['status'=>true , 'message'=>$msg , 'id'=>$id]);
            // }
            $msg = 'success';
            $notif = \App\Notification::where('id',$id)->first();
            if($notif->notif_type == 'single' && $notif->to_user == auth()->user()->id){
                $notif->delete();
                return response()->json(['status'=>true , 'message'=>$msg , 'id'=>$id]);
            }else{
                $user_ids = json_encode(\App\Device::where('device','!=','')->pluck('user_id')->toArray());
                if($notif->hidden_from != null):
                    //$notif->hidden_from = json_encode(array_push(json_decode($notif->hidden_from),auth()->user()->id),true);
                    $hidden_arr = array_push(json_decode($notif->hidden_from),auth()->user()->id);
                    $notif->hidden_from = json_encode($hidden_arr , true);
                    $notif->save();
                else:
                    $hidden_arr = array(auth()->user()->id);
                    $notif->hidden_from = json_encode($hidden_arr , true);
                    $notif->save();
                endif;
                
                return response()->json(['status'=>true , 'message'=>$msg , 'id'=>$id]);    
            }
            
        }
        return response()->json(['status'=>false , 'message'=>$msg]);
    }



    function company($id)
    {
        return \App\Company::where('user_id' , $id)->first();
        //return $this->belongsTo(Company::class);
    }

    function category($id)
    {
        $cat = \App\Category::find($id);
        return $cat != null ? $cat->name : null;
    }

    function day($key){
        $days_arr = ['Sat'=>'السبت' , 'Sun'=>'الأحد' , 'Mon'=>'الإثنين' ,'Tue'=>'الثلاثاء' , 'Wed'=>'الأربعاء' , 'Thu'=>'الخميس' , 'Fri'=>'الجمعة'] ;
         return $days_arr[$key];
    }

    function user($id){
        return App\User::find($id);
    }

    function type($id){
        return App\Category::find($id);
        
    }

    function countInvited($user_id){
        $invitations =  App\UserInvitation::where('invited_by',$user_id)->count();
        $last_invitations = App\UserDiscount::where('user_id',$user_id)->sum('registered_users_no');
        $net = $invitations - $last_invitations ;
        return $net ;
    }

    function countLastDiscounts($user_id){
        return App\UserDiscount::where('user_id',$user_id)->count();
    }

    function validateRules($errors, $rules) {

       $error_arr = [];

       foreach ($rules as $key => $value) {

           if( $errors->get($key) ) {

               array_push($error_arr, array($key => $errors->first($key)));
           }
       }

       return $error_arr;
    }

    function uploadImage($request, $name, $path = null, $width = null, $height = null)
    {
        if ($request->hasFile($name)):
            // Get File name from POST Form
            $image = $request->file($name);

            // Custom file name with adding Timestamp
            $filename = time() . '.' . str_random(20) . $image->getClientOriginalName();

            // Directory Path Save Images
            $path = public_path($path . $filename);

            // Upload images to Target folder By INTERVENTION/IMAGE
            $img = Image::make($image);

            // RESIZE IMAGE TO CREATE THUMBNAILS
            if (isset($width) || isset($height))
                $img->resize($width, $height, function ($ratio) {
                    $ratio->aspectRatio();
                });
            $img->save($path);

            // RETURN path to save in images tables DATABASE
            return $filename;
        endif;
    }

    function save64Img($base64_img, $path) {
        $image_data = base64_decode($base64_img);
        $source = imagecreatefromstring($image_data);
        $angle = 0;
        $rotate = imagerotate($source, $angle, 0); // if want to rotate the image
        $imageName = time() . str_random(20) . '.png';
        //$path = $path . $imageName;
        $path = public_path($path . $imageName);
        $imageSave = imagejpeg($rotate, $path, 100);
        return $imageName;
    }


    function uploading($inputRequest, $folderNam, $resize = []) {

        $imageName = time().'.'.$inputRequest->getClientOriginalExtension();

        if(! empty($resize)) {

            foreach($resize as $dimensions) {

                $destinationPath = public_path( $folderNam . '_' . $dimensions);

                $img = Image::make($inputRequest->getRealPath());

                $dimension = explode('x', $dimensions);

                $img->resize($dimension[0], $dimension[1], function ($constraint) {
                    $constraint->aspectRatio();

                });

                //$img->insert('public/web/images/logo-sm.png', 'bottom-right');

                $img->save($destinationPath. DIRECTORY_SEPARATOR .$imageName);
            }
        }

        $destinationPath = public_path('/' . $folderNam);
        $inputRequest->move($destinationPath, $imageName);

        return $imageName ? $imageName : FALSE ;

    }

    function getDefaultImage($image, $defaultImagePath)
    {
        return ($image != '') ? $image : $defaultImagePath;
    }

    function getAjaxCity(  $id,$col  ) {

        return json_encode( \App\District::where( $col, $id )->where('status',1)->select( 'id', 'name_' . App::getLocale() . ' as name' )->get() );
    }

    function getAjaxCard(  $id,$col  ) {

        return json_encode( \App\Card::where( $col, $id )->where('status',1)->select( 'id', 'name_' . App::getLocale() . ' as name' )->get() );
    }

    function getAjaxCardServices(  $id,$col  ) {

        return json_encode( \App\CardService::where( $col, $id )->where('status',1)->select( 'id', 'name_' . App::getLocale() . ' as name')->get() );
    }
    
    function sendOneSignalNotif($notif_type , $user_id=null , $title=null , $body=null ,$image=null , $type){
        
        if($notif_type == 'single'){
            $device = \App\Device::where('user_id',$user_id)->first();
            if($device):
                $device_id = $device->device;
            else:
                $device_id = '';
            endif;
            
            $user_ids = $user_id;
        
        }else{
            
            $device_id = \App\Device::where('device','!=','')->pluck('device')->toArray();
            
            $user_ids = json_encode(\App\Device::where('device','!=','')->pluck('user_id')->toArray());
            
        }
        
        
        $notif = new \App\Notification();
        $notif->msg = $body;
        $notif->title = $title;
        $notif->image = $image;
        $notif->to_user = $user_ids;
        $notif->type = $type;
        $notif->notif_type = $notif_type;
        $notif->save();
        
        $content = array(
            "en" => $body
        );
        
        $heading = array(
            "en" => $title
        );
        
        //onsignal app id: aa82b529-c4f2-4b75-a144-c1730b52b476

        //Key: NzllM2U1MDAtM2NjZS00MDg4LWFlYzQtM2FmM2IwNjEyMGQ3

        if($notif_type =='single'){
            $ids = $device_id ; // user->device_id
            $fields = array(
                'app_id' => "aa82b529-c4f2-4b75-a144-c1730b52b476",
                'include_player_ids' => [$ids],
                'data' => array("title" => $title ,"body" => $body,"image" => $image ,'type'=>$type),
                'contents' => $content ,
                'headings' => $heading
            );
        }else{
            $fields = array(
                'app_id' => "aa82b529-c4f2-4b75-a144-c1730b52b476",

                'included_segments' => array('All'),
                'data' => array("title" => $title ,"body" => $body,"image" => $image ,'type'=>$type),
                'contents' => $content ,
                'headings' => $heading
            );
        }

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic NzllM2U1MDAtM2NjZS00MDg4LWFlYzQtM2FmM2IwNjEyMGQ3'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    
    function sendEmail($fileMessagesPath, $vars = [], $file = '') {

    return \Illuminate\Support\Facades\Mail::send($fileMessagesPath, ['data' => $vars['data']], function ($m) use ($vars, $file) {

        $m->from($vars['from'], $vars['messagesTitle']);

        if($file != '') {
            //$m->attach($file);
            $m->attach($file->getRealPath(), [
                'as' => $file->getClientOriginalName(), 
                'mime' => $file->getMimeType()
            ]);
        }

        $m->to($vars['to'], $vars['fromName'])->subject($vars['subject']);

    }) ? TRUE : FALSE;
}

     function filter_mobile_number($mob_num) {

        $first_3_val = substr($mob_num, 0, 3);
        $sixth_val = substr($mob_num, 0, 6);
        $first_val = substr($mob_num, 0, 1);
        $mob_number = 0;
        $val = 0;
        if ($sixth_val == "009665") {
            $val = NULL;
            $mob_number = substr($mob_num, 2, 12);
        } elseif ($first_3_val == "+96") {
            $val = "966";
            $mob_number = substr($mob_num, 4);
        } elseif ($first_3_val == "966") {
            $val = NULL;
            $mob_number = $mob_num;
        } elseif ($first_val == "5") {
            $val = "966";
            $mob_number = $mob_num;
        } elseif ($first_3_val == "009") {
            $val = "9";
            $mob_number = substr($mob_num, 4);
        } elseif ($first_val == "0") {
            $val = "966";
            $mob_number = substr($mob_num, 1, 9);
        } else {
            $val = "966";
            $mob_number = $mob_num;
        }
    
        $real_mob_number = $val . $mob_number;
        return $real_mob_number;
    }
    
    function sendSms($message, $recepientNumber)
    {
            $getdata = http_build_query(
            $fields = array(
                "Username" => "s12-ETApromotion",
                "Password" => "ETA@2018",
                "Message" => $message,
                "RecepientNumber" =>  $recepientNumber,
                "ReplacementList" => "",
                "SendDateTime" => "0",
                "EnableDR" => False,
                "Tagname" => "ETA",
                "VariableList" => "0"
            ));

        $opts = array('http' =>
            array(
                'method' => 'GET',
                'header' => 'Content-type: application/x-www-form-urlencoded',

            )
        );

        $context = stream_context_create($opts);

        $results = file_get_contents('http://api.yamamah.com/SendSMSV2?' . $getdata, false, $context);
        
        return $results;
    }
    
    function sendActivationCode2($message, $recepientNumber)
    {
            $getdata = http_build_query(
            $fields = array(
                "Username" => "s12-Alshqardy",
                "Password" => "Alshqardy@2018",
                "Message" => $message,
                "RecepientNumber" =>  $recepientNumber,
                "ReplacementList" => "",
                "SendDateTime" => "0",
                "EnableDR" => False,
                "Tagname" => "Alshqardy",
                "VariableList" => "0"
            ));

        $opts = array('http' =>
            array(
                'method' => 'GET',
                'header' => 'Content-type: application/x-www-form-urlencoded',

            )
        );

        $context = stream_context_create($opts);

        $results = file_get_contents('http://api.yamamah.com/SendSMSV2?' . $getdata, false, $context);
        
        return $results;
    }


?>