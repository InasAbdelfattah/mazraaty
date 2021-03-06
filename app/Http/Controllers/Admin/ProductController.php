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
use App\Order;
use App\Basket;
use App\Item;
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

        $cat = Category::where('parent_id',0)->where('status' , 1)->get();

        $cat->map(function ($q) {
            $subcats = Category::where('parent_id',$q->id)->where('status',1)->select('id','name')->get();
            $subcats->map(function ($q) {
                $q->products = json_encode(Product::where('subcategory_id',$q->id)->where('status',1)->select('id','name')->get());
            });
            $q->subcats = json_encode($subcats);
        });

        $type = 'products';
        $list = Product::all();

        return view('admin.products.index',compact('products' ,'cat', 'type' ,'list'));
    }


    public function search(Request $request)
    {
        
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        $products = [] ;

        $query = Product::select();

        if($request->id):
            $query->where('id',$request->id);
        endif;

        if($request->cat_id != null):
            $query->where('category_id',$request->cat_id);
        endif;

        if($request->subcat_id != null):
            $query->where('subcategory_id',$request->subcat_id);
        endif;


        if($request->status != null):
            $status = (int)$request->status;
            $query->where('status',$status);
        endif;
        
        $products = $query->orderBy('id','DESC')->get();

        $cat = Category::where('parent_id',0)->where('status' , 1)->get();

        $cat->map(function ($q) {
            $subcats = Category::where('parent_id',$q->id)->where('status',1)->select('id','name')->get();
            $subcats->map(function ($q) {
                $q->products = json_encode(Product::where('subcategory_id',$q->id)->where('status',1)->select('id','name')->get());
            });
            $q->subcats = json_encode($subcats);
        });

        $type = 'search';

        $list = Product::all();

        return view('admin.products.index',compact('products' ,'cat', 'type','list'));
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
        if (!Gate::allows('products_manage')) {
            return abort(401);
        }
     
        // Declare Validation Rules.
        $rules = [
            'name' => 'required|min:3|max:50|unique:products,name',
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
        if (!Gate::allows('products_manage')) {
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
            'name' => 'required|min:3|max:50|unique:products,name,'.$id,
            'description' => 'required|min:3|max:10000',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'measurement_id' => 'required',
            'status' => 'required',
            'image' => 'image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($validator->errors());
        }

        $product = Product::findOrFail($id);

        if($request->status == 0){
            $orders = Order::whereIn('status',[0,1])->pluck('basket_id')->toArray();
            if(count($orders) > 0){
                $baskets = Basket::whereIn('id',$orders)->pluck('id')->toArray();
                if(count($baskets) > 0){
                    $item = Item::whereIn('basket_id',$baskets)->where('itemable_id',$product->id)->where('itemable_type','App\Product')->first();
                    if($item){
                        return redirect()->route('products.index')->with('error', 'لا يمكن تعطيل المنتج لوجوده فى طلبات جارية ');
                    }
                }
            }
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->measurement_id = $request->measurement_id;
        $product->status = $request->status;
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

        if ($model) {

            

            if($model->status != $request->status) {
                if ($request->status == 1) {
                    $msg = 'تم تفعيل المنتج';
                } else {
                    $orders = Order::whereIn('status',[0,1])->pluck('basket_id')->toArray();
            if(count($orders) > 0){
                $baskets = Basket::whereIn('id',$orders)->pluck('id')->toArray();
                if(count($baskets) > 0){
                    $item = Item::whereIn('basket_id',$baskets)->where('itemable_id',$model->id)->where('itemable_type','App\Product')->first();
                    if($item){
                        return response()->json([
                            'status' => false,
                            'message' => 'لا يمكن تعطيل المنتج لوجوده فى طلبات جارية ',
                        ]);
                    }
                }
            }
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

        if (!Gate::allows('products_manage')) {
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
        if (!Gate::allows('products_manage')) {
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
        if (!Gate::allows('products_manage')) {
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
