<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Gate;
use App\Product;
use App\Category;
use App\Offer;
use App\MeasurementUnit;
use UploadImage;

use App\Order;
use App\Basket;
use App\Item;

class OfferController extends Controller
{

    /**
     * @var Category
     */

    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/offers/';
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
        $offers = Offer::join('products','offers.product_id','products.id')->select('offers.*','products.name as product_name')->get();

        $cat = Category::where('parent_id',0)->where('status' , 1)->get();

        $cat->map(function ($q) {
            $subcats = Category::where('parent_id',$q->id)->where('status',1)->select('id','name')->get();
            $subcats->map(function ($q) {
                $q->products = json_encode(Product::where('subcategory_id',$q->id)->where('status',1)->select('id','name')->get());
            });
            $q->subcats = json_encode($subcats);
        });

        $type = 'offers';
        $list = Product::all();


        return view('admin.offers.index',compact('offers' , 'type' ,'list','cat'));
    }

    public function search(Request $request)
    {
        
        if (!Gate::allows('products_manage')) {
            return abort(401);
        }

        $offers = [] ;

        $query = Offer::select();

        if($request->id):
            $query->where('offers.product_id',$request->id);
        endif;

        if($request->cat_id != null):
            $query->where('offers.category_id',$request->cat_id);
        endif;

        if($request->subcat_id != null):
            $query->where('offers.subcategory_id',$request->subcat_id);
        endif;


        if($request->status != null):
            $query->where('offers.is_available',$request->status);
        endif;
        
        $offers = $query->join('products','offers.product_id','products.id')->select('offers.*','products.name as product_name')->get();

        // $offers = Offer::join('products','offers.product_id','products.id')->select('offers.*','products.name as product_name')->get();

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

        return view('admin.offers.index',compact('offers' , 'type' ,'list','cat'));
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
            $subcats = Category::where('parent_id',$q->id)->where('status',1)->select('id','name')->get();
            $subcats->map(function ($q) {
                $q->products = json_encode(Product::where('subcategory_id',$q->id)->where('status',1)->select('id','name')->get());
            });
            $q->subcats = json_encode($subcats);
        });
        return view('admin.offers.create' , compact('cates' , 'measurements'));
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
            //'name' => 'required|min:3|max:50',
            //'description' => 'required|min:3|max:10000',
            'price' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:1',
            'product_id' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            //'measurement_id' => 'required',
            'is_available' => 'required',
            //'image' => 'image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            //dd($validator->errors());

           // return redirect()->back()->withErrors($validator)->withInput();

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($validator->errors());
        }

        $product = Product::find($request->product_id);
        //dd($product);
        // if(!$product):
        //     return redirect()->back()->with('error','منتج غير متاح');
        // endif;
        //`name`, `image`, `description`, `price`, `amount`, `product_id`, `measurement_id`, `is_available`, `status`,'category_id','subcategory_id'

        $offer = new Offer;
        $offer->name = $request->name ? $request->name : '';
        $offer->description = $request->description ? $request->description : '';
        $offer->price = $request->price;
        $offer->amount = $request->amount;
        $offer->product_id = $request->product_id;
        $offer->category_id = $request->category_id;
        $offer->subcategory_id = $request->subcategory_id;
        $offer->measurement_id = $request->measurement_id ? $request->measurement_id : 0;
        $offer->is_available = $request->is_available;
        $offer->status = 1;

        $sale_product_price = $request->price /$request->amount;
        $offer->insale_product_price = $sale_product_price;
        $offer->product_price = $product->price;
        $offer->sale = $product->price - $sale_product_price;
        $offer->sale_percntage = (($product->price - $sale_product_price)/$product->price)*100;

        if ($request->hasFile('image')):
            $offer->image = uploadImage($request, 'image', $this->public_path);
        else:
            $offer->image = '';
        endif;
        $offer->save();
        session()->flash('success', 'لقد تم إضافة العرض بنجاح ');
        return redirect(route('offers.index'));
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

        $offer = Offer::findOrFail($id);
        
        return view('admin.offers.show')->with(compact('offer'));
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

        $offer = Offer::findOrFail($id);
        $measurements = MeasurementUnit::where('status',1)->get();
        $cates = Category::where('parent_id',0)->where('status' , 1)->get();
        $cates->map(function ($q) {
            $q->subcats = json_encode(Category::where('parent_id',$q->id)->where('status',1)->select('id','name')->get());
        });
        return view('admin.products.edit' , compact('offer', 'cates', 'measurements'));
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
            'amount' => 'required|numeric|min:1',
            'product_id' => 'required',
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
        //`name`, `image`, `description`, `price`, `amount`, `product_id`, `measurement_id`, `is_available`, `status`,'category_id','subcategory_id'
        $offer = Offer::findOrFail($id);
        $offer->name = $request->name;
        $offer->description = $request->description;
        $offer->price = $request->price;
        $offer->amount = $request->amount;
        $offer->product_id = $request->product_id;
        $offer->category_id = $request->category_id;
        $offer->subcategory_id = $request->subcategory_id;
        $offer->measurement_id = $request->measurement_id;
        $offer->is_available = $request->is_available;
        $offer->status = 1;
        if ($request->hasFile('image')):
            $offer->image = uploadImage($request, 'image', $this->public_path);
        else:
            $offer->image = '';
        endif;
        $offer->save();
        session()->flash('success', 'لقد تم تعديل العرض بنجاح' . $offer->name);
        return redirect(route('offers.index'));
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request ,$id)
    {
        if (!Gate::allows('products_manage')) {
            return abort(401);
        }

        $model = Offer::findOrFail($request->id);

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

    public function activateOffer(Request $request)
    {

        $model = Offer::findOrFail($request->id);

        if ($model) {
            
            if($model->is_available != $request->status) {
                if ($request->is_available == 1) {
                    // $orders = Order::whereIn('status',[0,1])->pluck('basket_id')->toArray();
                    // if(count($orders) > 0){
                    //     $baskets = Basket::whereIn('id',$orders)->pluck('id')->toArray();
                    //     if(count($baskets) > 0){
                    //         $item = Item::whereIn('basket_id',$baskets)->where('itemable_id',$model->id)->where('itemable_type','App\Offer')->first();
                    //         if($item){
                    //             return response()->json([
                    //                 'status' => false,
                    //                 'message' => 'لا يمكن تعطيل العرض لوجوده فى طلبات جارية ',
                    //             ]);
                    //         }
                    //     }
                    // }
                    $msg = 'تم الغاء اتاحة العرض';
                } else {
                    $msg = 'تم إتاحة العرض';
                }
                $model->is_available = $request->status;
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

            $model = Offer::findOrFail($id);

            $model->delete();
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $request->id
            ]
        ]);
    }
}
