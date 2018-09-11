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

        if (!Gate::allows('offers_manage')) {
            return abort(401);
        }

        /**
         * Get all products
         */
        $offers = Offer::all();
        return view('admin.offers.index',compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('offers_manage')) {
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
        
        if (!Gate::allows('offers_manage')) {
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
        $offer = new Offer;
        $offer->name = $request->name ? $request->name : '';
        $offer->description = $request->description ? $request->description : '';
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
        session()->flash('success', 'لقد تم إضافة المنتج بنجاح : '  . $offer->name);
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
        if (!Gate::allows('offers_manage')) {
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
        if (!Gate::allows('offers_manage')) {
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
        if (!Gate::allows('offers_manage')) {
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
        //dd($request);

//        if ($model->products->count() > 0) {
//            return response()->json([
//                'status' => false,
//                'message' => "عفواً, لا يمكنك حذف النوع ($model->name) نظراً لوجود مراكز ملتحقة بهذا النوع"
//            ]);
//        }

        if ($model) {
            
            if($model->status != $request->status) {
                if ($request->status == 1) {
                    $msg = 'تم تفعيل العرض';
                } else {
                    $msg = 'تم تعطيل العرض';
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

        if (!Gate::allows('offers_manage')) {
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
