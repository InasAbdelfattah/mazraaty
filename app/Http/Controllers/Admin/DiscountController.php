<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Gate;
use App\Discount;
use App\Coupon;
use App\Libraries\PushNotification;
use App\User;
use App\Notification;

class DiscountController extends Controller
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
    public function index(Request $request)
    {

        if (!Gate::allows('discounts_manage')) {
            return abort(401);
        }

        /**
         * Get all discounts
         */
        $discounts = Coupon::all();
        return view('admin.discounts.index',compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!Gate::allows('discounts_manage')) {
            return abort(401);
        }

        return view('admin.discounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('discounts_manage')) {
            return abort(401);
        }
     
        // Declare Validation Rules.
        $rules = [
            'times' => 'required|numeric|min:1',
            'code' => 'required|numeric|min:1',
            'ratio' => 'required|numeric|min:1',
            'start_from' => 'date_format:"Y-m-d"|required',
            'end_at' => 'date_format:"Y-m-d"|required|after:start_from',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($validator->errors());
        }
            
        $discount = new Coupon;
        $discount->times = $request->times;
        $discount->code = $request->code;
        $discount->ratio = $request->ratio;
        $discount->from = $request->start_from;
        $discount->to = $request->end_at;
        $discount->save();

        $title = 'كود خصم من مزرعتى';
        $body = 'كود الخصم هو : '.$discount->code. ' وجارى استخدامه فى الفترة من : '.$discount->from.'الى : '.$discount->to;
        //$data = ['title' => $title , 'body'=>$body];
        $data = [];

        $r = $this->push->sendPushNotification(null , $data, $title, $body,'global');
        
        $users = User::whereDoesntHave('roles')->where('is_admin',0)->select('id')->get();

        $ids = $users->pluck('id');

        $notif_data = [];

        foreach ($users as $key => $user) {

            $notif_data[] = array(
                'user_id' => $user->id,
                'user_ids' => null,
                'push_type' => 'global',
                'target_id' => null,
                'target_type' => 'coupon',
                'title' => $title,
                'body' => $body,
                'image' => null,
                'is_read' => 0,
                'data' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

        }

        Notification::insert($notif_data);

        //return $r;

        session()->flash('success', 'لقد تم إضافة كود خصم بنجاح');
        return redirect(route('discounts.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('discounts_manage')) {
            return abort(401);
        }

        $discount = Coupon::findOrFail($id);
        
        return view('admin.discounts.show')->with(compact('discount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('discounts_manage')) {
            return abort(401);
        }

        $discount = Coupon::findOrFail($id);
        return view('admin.discounts.edit' , compact('discount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('discounts_manage')) {
            return abort(401);
        }

        $rules = [
            'times' => 'required|numeric|min:1',
            'code' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'ratio' => 'required|numeric|min:1',
            'start_from' => 'date_format:"Y-m-d"|required',
            'end_at' => 'date_format:"Y-m-d"|required|after:start_from',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($validator->errors());
        }
            
        $discount = Coupon::findOrFail($id);
        $discount->times = $request->times;
        $discount->code = $request->code;
        $discount->ratio = $request->ratio;
        $discount->from = $request->start_from;
        $discount->to = $request->end_at;
        $discount->save();
        session()->flash('success', 'لقد تم تعديل كود الخصم بنجاح');
        return redirect(route('discounts.index'));   
    }

    public function search(Request $request)
    {
        if (!Gate::allows('discounts_manage')) {
            return abort(401);
        }

        $discounts = [] ;

        if ($request->from_date != '' && $request->to_date != '' && $request->to_date > $request->from_date) {
            
            $discounts = Coupon::whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date)->get();
        }else{

            return back()->with(compact('discounts'))->with('error','من فضلك يرجى اختيار فترة زمنية صحيحة');

        }
        
        $type = 'search';
        return view('admin.discounts.index' , compact('discounts'));

    }
}
