<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Gate;
use App\Product;
use App\Category;
use App\MeasurementUnit;
use App\Libraries\PushNotification;
use App\User;
use App\Notification;

class ProductController extends Controller
{

    /**
     * @var Category
     */

    public $public_path;
    public $push;

    public function __construct(PushNotification $push)
    {
        $this->public_path = 'files/products/';
        $this->push = $push;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Gate::allows('products_manage')) {
            return abort(401);
        }

        /**
         * Get all products
         */
        $products = Product::all();
        return view('admin.products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!Gate::allows('products_manage')) {
            return abort(401);
        }
        $measurements = MeasurementUnit::where('status',1)->get();
        $cates = Category::where('parent_id',0)->where('status' , 1)->get();
        $cates->map(function ($q) {
            $q->subcats = json_encode(Category::where('parent_id',$q->id)->where('status',1)->select('id','name')->get());
        });
        return view('admin.products.create' , compact('cates' , 'measurements'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }
     
        // Declare Validation Rules.
        $rules = [
            'name' => 'required|min:3|max:50',
            'description' => 'required|min:3|max:10000',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'measurement_id' => 'required',
            'is_available' => 'required',
            'image' => 'image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            //dd($validator->errors());

           // return redirect()->back()->withErrors($validator)->withInput();

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($validator->errors());
        }
            // `name`, `description`, `image`, `price`, `category_id`, `subcategory_id`, `measurement_id`, `sales_no`, `is_bestseller`, `is_available`, `status`
        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->measurement_id = $request->measurement_id;
        $product->is_available = $request->is_available;
        $product->status = 1;
        $product->sales_no = 0;
        $product->is_bestseller = 0;
        if ($request->hasFile('image')):
            $product->image = uploadImage($request, 'image', $this->public_path);
        else:
            $product->image = '';
        endif;
        $product->save();

        $title = 'كود خصم من مزرعتى';
        $body = 'تم اضافة منتج جديد وهو : '.$product->name;
        //$data = ['title' => $title , 'body'=>$body];
        $data = [];

        $r = $this->push->sendPushNotification(null , $data, $title, $body,'global');
        
        $users = User::whereDoesntHave('roles')->where('is_admin',0)->select('id')->get();
        //$ids = User::where('is_admin',0)->pluck('id')->toArray();

        //$ids = $users->pluck('id');

        $notif_data = [];

        foreach ($users as $key => $user) {

            $notif_data[] = array(
                'user_id' => $user->id,
                'user_ids' => null,
                'push_type' => 'global',
                'target_id' => null,
                'target_type' => 'product',
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

        session()->flash('success', 'لقد تم إضافة المنتج بنجاح' . $product->name);
        return redirect(route('products.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('products_manage')) {
            return abort(401);
        }

        $product = Product::findOrFail($id);
        
        return view('admin.products.show')->with(compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }

        $product = Product::findOrFail($id);
        $measurements = MeasurementUnit::where('status',1)->get();
        $cates = Category::where('parent_id',0)->where('status' , 1)->get();
        $cates->map(function ($q) {
            $q->subcats = json_encode(Category::where('parent_id',$q->id)->where('status',1)->select('id','name')->get());
        });
        return view('admin.products.edit' , compact('product', 'cates', 'measurements'));
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
        if (!Gate::allows('products_manage')) {
            return abort(401);
        }

        $rules = [
            'name' => 'required|min:3|max:50',
            'description' => 'required|min:3|max:10000',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'measurement_id' => 'required',
            'is_available' => 'required',
            'image' => 'image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($validator->errors());
        }

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->measurement_id = $request->measurement_id;
        $product->is_available = $request->is_available;
        if ($request->hasFile('image')):
            $product->image = uploadImage($request, 'image', $this->public_path);
        endif;

        $product->save();
        session()->flash('success', 'لقد تم تعديل المنتج بنجاح' . $product->name);
        return redirect(route('products.index'));
        
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

    public function activateProduct(Request $request)
    {

        $model = Product::findOrFail($request->id);
        //dd($request);

//        if ($model->companies->count() > 0) {
//            return response()->json([
//                'status' => false,
//                'message' => "عفواً, لا يمكنك حذف النوع ($model->name) نظراً لوجود مراكز ملتحقة بهذا النوع"
//            ]);
//        }

        if ($model) {
            
            // $user_cards = UserCard::where('card_id',$model->id)->where('to','>=',date('Y-m-d'))->count();
            // if($user_cards > 0 && $request->status == 0){
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'لا يمكن تعطيل البطاقة لوجود مستخدمين مشترين للبطاقة',
            //     ]);
            // }

            if($model->status != $request->status) {
                if ($request->status == 1) {
                    $msg = 'تم تفعيل المنتج';
                } else {
                    $msg = 'تم تعطيل المنتج';
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


    /**
     * Remove User from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function groupDelete(Request $request)
    {

        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }

        $ids = $request->ids;
        foreach ($ids as $id) {

            $model = Card::findOrFail($id);

            if ($model->cardServices->count() > 0) {
                return response()->json([
                    'status' => false,
                    'message' => "عفواً, لا يمكنك حذف النوع ($model->name) نظراً لوجود مراكز ملتحقة بهذا النوع"
                ]);
            }

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
        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }

        $model = Card::findOrFail($request->id);

//        if ($model->companies->count() > 0) {
//            return response()->json([
//                'status' => false,
//                'message' => "عفواً, لا يمكنك حذف النوع ($model->name) نظراً لوجود مراكز ملتحقة بهذا النوع"
//            ]);
//        }

        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }

    public function deleteService(Request $request)
    {
        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }

        $model = CardService::findOrFail($request->id);

        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }
}
