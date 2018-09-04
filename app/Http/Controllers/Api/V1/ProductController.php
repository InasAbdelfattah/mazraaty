<?php

namespace App\Http\Controllers\Api\V1;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use MeasurementUnit;
use Category ;

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

            $q->category = $category != null ? $category->name : null ;
            $q->subcategory = $subcategory != null ? $subcategory->name : null ;
            $q->measurementUnit = $measurementUnit != null ? $measurementUnit->name : null ;
            $q->image= $request->root() . '/' . $this->public_path . $q->image ;
            
        });

        /**
         * Return Data Array
         */

        return response()->json([
            'status' => true,
            'data' => $products
        ]);

    }

}
