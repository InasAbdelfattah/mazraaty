<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Card;
use App\CardService;
use App\Category;
use App\Center;
use App\CenterService;
use App\City;
use UploadImage;
use Validator;


class CenterController extends Controller
{

    /**
     * @var Category
     */

    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/centers/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        /**
         * Get all Categories
         */
        $centers = Center::with('centerServices')->get();

        ## SHOW CATEGORIES LIST VIEW WITH SEND CATEGORIES DATA.
        return view('admin.centers.index',compact('centers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }
        $categories = Category::where('status' , 1)->get();
        $areas = City::where('status' , 1)->get();
        return view('admin.centers.create' , compact('categories' , 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//dd($request);
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $rules = [
            'name_ar' => 'required|min:3|max:50',
            'name_en' => 'required|min:3|max:50',
            'description_ar' => 'required|min:3|max:2500',
            'description_en' => 'required|min:3|max:2500',
            'phone' => 'required|min:8|max:25',
            //'email' => 'email|min:10|max:75',
            //'category_id' => 'required',
            'area_id' => 'required',
            'city_id' => 'required',
           // 'image' => 'image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

           // return redirect()->back()->withErrors($validator)->withInput();

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($valErrors);
        }

        $center = new Center;
        $center->name_ar = $request->name_ar;
        $center->name_en = $request->name_en;
        $center->description_ar = $request->description_ar;
        $center->description_en = $request->description_en;
        $center->area_id = $request->area_id;
        $center->city_id = $request->city_id;
        $center->lat = $request->lat;
        $center->lng = $request->lng;
        $center->address = $request->address ;
        $center->phone = $request->phone ;
        $center->email = $request->email ? $request->email : '' ;
        $center->status = $request->status ;
        $center->created_by = auth()->user()->id;
        if ($request->hasFile('image')):
            $center->photo = UploadImage::uploadImage($request, 'image', $this->public_path);
        else:
            $center->photo = '';
        endif;
        $center->save();
        /*if($request->has('service') && $request->service != ''){
            if(count($request->service) > 0){
                foreach($request->service as $s){
                    $service = new CenterService();
                    $service->card_id = $s['card_id'];
                    $service->service_id = $s['service_id'];
                    $service->center_id = $center->id;
                    $service->save();
                }
            }

        }*/

        /**
         * @ Store Image With Image Intervention.
         */

        if ($center->id) {
            $title = '';
            $body = 'تم اضافة مركز جديد وهو '.$center->name_ar;
            
            $notif = sendOneSignalNotif('all' ,null , $title , $body ,null , 'card');
            
            
            session()->flash('success', 'لقد تم إضافة المركز بنجاح' . $center->name_ar . 'برجاء تعيين بطاقات للمركز');
            session()->flash('centerId', $center->id);
            //return redirect(route('centers.index'));
            return redirect(route('centers.edit', $center->id));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $center = Center::with('centerServices')->findOrFail($id);

        $centerCards = CenterService::where('center_id',$id)->get();
        
        $centerCards->map(function ($q) {
            $q->card= Card::where('id',$q->card_id)->select('id','name_ar')->first();
         });
        
        //$cards = CenterService::where('center_id',$id)->groupBy('card_id')->get();
        // if($cards){
        //     $cardIds = $cards ->pluck('card_id')->toArray();
        //     $card = Card::whereIn('id', $cardIds )->select('id','name_ar','price','expiration')->get();
        //     $card ->map(function ($q) use($id) {
        //         $q->services= CenterService::where('center_id',$id)->where('card_id',$q->id)->select('id','price','service_id')->get();
        //         $q->services->map(function ($q) {
        //             $q->service_name= CardService::where('id',$q->service_id)->select('name_ar')->first();
        //          });
        //     });

        //     $centerCards = $card ;
        // }else{
        //     $centerCards = [];
        // }


        return view('admin.centers.show')->with(compact('center' , 'centerCards'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $center = Center::find($id);
        $categories = Category::where('status' , 1)->get();
        $areas = City::where('status' , 1)->get();

        $cards = CenterService::where('center_id',$id)->groupBy('card_id')->get();
        // if($cards){
        //     $cardIds = $cards ->pluck('card_id')->toArray();
        //     $card = Card::whereIn('id', $cardIds )->select('id','name_ar','price','expiration')->get();
        //     $card ->map(function ($q) use($id) {
        //         $q->services= CenterService::where('center_id',$id)->where('card_id',$q->id)->select('id','price','service_id')->get();
        //         $q->services->map(function ($q) {
        //             $q->service_name= CardService::where('id',$q->service_id)->select('name_ar')->first();
        //         });
        //     });

        //     //dd($card);
        //     $centerCards = $card ;
        // }else{
        //     $centerCards = [];
        // }
        //dd($center);
        
        $centerCards = CenterService::where('center_id',$id)->get();
        
        $centerCards->map(function ($q) {
            $q->card= Card::where('id',$q->card_id)->select('id','name_ar')->first();
         });
         
        return view('admin.centers.edit')->with(compact('center' , 'areas' , 'categories' ,'centerCards'));
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
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $center = Center::findOrFail($id);
        $center->name_ar = $request->name_ar;
        $center->name_en = $request->name_en;
        $center->description_ar = $request->description_ar;
        $center->description_en = $request->description_en;
        $center->area_id = $request->area_id;
        $center->city_id = $request->city_id;
        $center->lat = $request->lat;
        $center->lng = $request->lng;
        $center->address = $request->address ;
        $center->phone = $request->phone ;
        $center->email = $request->email ? $request->email :  $center->email;
        $center->status = $request->status ;
        $center->created_by = auth()->user()->id;
        if ($request->hasFile('image')):
            $center->photo = UploadImage::uploadImage($request, 'image', $this->public_path);
        endif;
        $center->save();
        if($request->has('service') && $request->service != ''){
            if(count($request->service) > 0){
                foreach($request->service as $s){
                    $service = new CenterService();
                    $service->card_id = $s['card_id'];
                    $service->service_id = $s['service_id'];
                    $service->center_id = $center->id;
                    $service->save();
                }
            }

        }

        if ($center->save()) {
            session()->flash('success', 'لقد تم تعديل نوع البطاقة بنجاح' . $center->name_ar);

            return redirect(route('centers.index'));
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

   
    /**
     * Remove User from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function groupDelete(Request $request)
    {

        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $ids = $request->ids;
        foreach ($ids as $id) {

            $model = Center::findOrFail($id);

            // if ($model->cardServices->count() > 0) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => "عفواً, لا يمكنك حذف النوع ($model->name) نظراً لوجود مراكز ملتحقة بهذا النوع"
            //     ]);
            // }

            $model->delete();
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $request->id
            ]
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $model = Center::findOrFail($request->id);

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

    public function deleteService(Request $request)
    {
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $model = CenterService::findOrFail($request->id);

        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }

    public function activateCenter(Request $request)
    {

        $model = Center::findOrFail($request->id);
        //dd($request);

//        if ($model->companies->count() > 0) {
//            return response()->json([
//                'status' => false,
//                'message' => "عفواً, لا يمكنك حذف النوع ($model->name) نظراً لوجود مراكز ملتحقة بهذا النوع"
//            ]);
//        }

        if ($model) {

            if($model->status != $request->status) {
                if ($request->status == 1) {
                    $msg = 'تم تفعيل المركز';
                } else {
                    $msg = 'تم تعطيل المركز';
                }
                $model->status = $request->status;
                if ($model->save()) {
                    return response()->json([
                        'status' => true,
                        'message' => $msg,
                        'id' => $model->id
                    ]);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'لم يحدث تغيير',
                ]);
            }

        } else {
            return response()->json([
                'status' => false,
                'message' => 'فشل',
            ]);
        }
    }

    public function setSession(Request $request){
        //dd($request);
        if(!$request->has('center_id')){
            session()->flash('error', 'يرجى استكمال البيانات');
            return redirect()->back();
            return response()->json([
                'status' => false,
                'message' => 'لا بد من حفظ بيانات المركز الاساسية اولا',
            ]);
        }
        
        if(!$request->has('category_id') || $request->category_id == null){
            session()->flash('error', 'يرجى استكمال بيانات البطاقة');
            return redirect()->back();
            // return response()->json([
            //     'status' => false,
            //     'message' => 'يرجى استكمال البيانات',
            // ]);
        }
        
        if(!$request->has('card_id') || $request->card_id == null){
            session()->flash('error', 'يرجى استكمال بيانات البطاقة');
            return redirect()->back();
            // return response()->json([
            //     'status' => false,
            //     'message' => 'يرجى استكمال البيانات',
            // ]);
        }
        
        // if(!$request->has('price') || $request->price == null){
        //     session()->flash('error', 'يرجى استكمال بيانات البطاقة');
        //     return redirect()->back();
            
        // }
        
        $center_service = CenterService::where('center_id',$request->center_id)->where('card_id',$request->card_id)->first();
        if($center_service){
             session()->flash('error', 'تم ادخال  هذه البطاقة من قبل');
            return redirect()->back();
            return response()->json([
                'status' => false,
                'message' => 'تم ادخال  هذه البطاقة من قبل',
            ]);
        }

        //session(['category_id' => $request->category_id , 'card_id' => $request->card_id,'services' => $request->service]);
        //$value = ['category_id' => $request->category_id , 'card_id' => $request->card_id,'services' => $request->service];
        //session()->push('services',$value);
        //$request->session()->put('card' . $request->v, 'value');
        
        // if($request->has('service') && $request->service != ''){
        //     dd($request->service);
        //     if(count($request->service) > 0){
        //         foreach($request->service as $s){
        //             $service = new CenterService();
        //             $service->card_id = $request->card_id;
        //             $service->service_id = 0;
        //             $service->price = '';
        //             $service->center_id = $request->center_id;
        //             $service->save();
        //         }

        //         $request->session()->forget('centerId');
        //     }

        // }
        
        $service = new CenterService();
        $service->card_id = $request->card_id;
        $service->service_id = 0;
        $service->price = $request->price ? $request->price : '';
        $service->center_id = $request->center_id;
        $service->save();
        //dd($request);
        return redirect()->route('centers.edit',$request->center_id);
        return response()->json([
            'status' => true,
            'card_id' => $request->card_id,
            'service' => $request->service,
            'card' => Card::find($request->card_id) ? Card::find($request->card_id)->name_ar : '',
            'category' => Category::find($request->category_id) ? Category::find($request->category_id)->name_ar : '',
        ]);
    }

    public function editService(Request $request){
        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }

        $service = CenterService::find($request->serviceId);


        if ($request->price == '') {
            return response()->json([
                'status' => false,
                'message' => 'من فضلك قم بكتابة سعر الخدمة   ',
            ]);
        }

        if($service){
            $service->price = $request->price;


            if ($service->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'تم الحفظ',
                    'id' => $service->id,
                    'price' => $service->price ,

                ]);
            }
        } else {
            return response()->json([
            'status' => false,
            'message' => 'Fail',
            ]);
        }
    }

    public function deleteCard(Request $request){
        if (!Gate::allows('orders_manage')) {
            return abort(401);
        }
        
        $cardCenter = CenterService::find($request->id);
        if($cardCenter):
            $cardCenter->delete();
            return response()->json([
                'status' => true,
                'message' => 'تم الحفظ',
                'id' => $request->id,
            ]);
        endif;

        // $services = CenterService::where('center_id',$request->centerId)->where('card_id',$request->id)->get();
        // if(count($services) > 0){
        //     foreach($services as $service){
        //         $service->delete();
        //     }
        //     return response()->json([
        //         'status' => true,
        //         'message' => 'تم الحفظ',
        //         'id' => $request->id,
        //     ]);
        // }

        return response()->json([
            'status' => false,
            'message' => 'Fail',
        ]);

    }

}
