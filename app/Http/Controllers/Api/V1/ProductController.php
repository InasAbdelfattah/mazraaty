<?php

namespace App\Http\Controllers\Api\V1;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\MeasurementUnit;
use App\Category ;

class ProductController extends Controller
{

    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/products/';
    }
   
    public function index(Request $request)
    {
        /**
         * Set Default Value For Skip Count To Avoid Error In Service.
         * @ Default Value 15...
         */
        if (isset($request->pageSize)):
            $pageSize = $request->pageSize;
        else:
            $pageSize = 15;
        endif;
        /**
         * SkipCount is Number will Skip From Array
         */
        $skipCount = $request->skipCount;
        $itemId = $request->itemId;

        $currentPage = $request->get('page', 1); // Default to 1
        
        $query = Product::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->select();        
        
        if ($request->category_id) :
            $query->where('category_id', $request->category_id);
        endif;
        
        if ($request->subcategory_id):
            $query->where('subcategory_id', $request->subcategory_id);
        endif;

        if ($request->product_name):
            $query->where('name', $request->product_name);
        endif;
        
        /**
         * @ If item Id Exists skipping by it.
         */
        if ($itemId) {
            $query->where('id', '<=', $itemId);
        }

        /**
         * @@ Skip Result Based on SkipCount Number And Pagesize.
         */
        $query->skip($skipCount);
        $query->take($pageSize);

        /**
         * @ Get All Data Array
         */

        $products = $query->select('id','name','description' , 'image' , 'price' ,'category_id' ,'subcategory_id' , 'measurement_id' ,'sales_no','is_bestseller', 'is_available','created_at')->get();

        $products->map(function ($q) use($request){

            $category = Category::find($q->category_id);
            $subcategory = Category::find($q->subcategory_id);
            $measurementUnit = MeasurementUnit::find($q->measurement_id);

            $q->category = $category != null ? $category->name : '' ;
            $q->subcategory = $subcategory != null ? $subcategory->name : '' ;
            $q->measurementUnit = $measurementUnit != null ? $measurementUnit->name : '' ;
            $q->image= $request->root() . '/' . $this->public_path . $q->image ;
            
        });

        /**
         * Return Data Array
         */

        return response()->json([
            'status' => 200,
            'data' => $products
        ]);

    }

    public function details(Request $request){

        $rules = [
            'productId' => 'required'  
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json(['status'=>400,'errors' => $validator->errors()->all()]);
        }

        $product = Product::where('id',$request->productId)->where('status',1)->select('id','name','description' , 'image' , 'price' ,'category_id' ,'subcategory_id' , 'measurement_id' ,'sales_no','is_bestseller', 'is_available','created_at')->first();

        if(!$product){
            return response()->json([
                'status' => 400,
                'errors' => ['المنتج غير موجود بالتطبيق'],
                'data' => []
            ]);
        }

        $category = Category::find($product->category_id);
        $subcategory = Category::find($product->subcategory_id);
        $measurementUnit = MeasurementUnit::find($product->measurement_id);

        $product->category = $category != null ? $category->name : '' ;
        $product->subcategory = $subcategory != null ? $subcategory->name : '' ;
        $product->measurementUnit = $measurementUnit != null ? $measurementUnit->name : '' ;
        $product->image= $request->root() . '/' . $this->public_path . $product->image ;

        return response()->json([
            'status' => 200,
            'data' => $product
        ]);
    }

}
