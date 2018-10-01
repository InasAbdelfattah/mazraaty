<?php

namespace App\Http\Controllers\Api\V1;

use App\Product;
use App\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\MeasurementUnit;
use App\Category ;

class OfferController extends Controller
{

    public $public_path;
    public $public_product_path;

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
        
        $query = Offer::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->select();        
        
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
//`name`, `image`, `description`, `price`, `amount`, `product_id`, `measurement_id`, `is_available`, `status`, `created_at`, `updated_at`, `category_id`, `subcategory_id
        $offers = $query->select('id', 'price' ,'category_id' ,'subcategory_id' , 'measurement_id' ,'product_id','amount', 'is_available','created_at')->get();

        $offers->map(function ($q) use($request){

            $category = Category::find($q->category_id);
            $subcategory = Category::find($q->subcategory_id);
            //$measurementUnit = MeasurementUnit::find($q->measurement_id);
            $product = Product::find($q->product_id);

            $q->category = $category != null ? $category->name : '' ;
            $q->subcategory = $subcategory != null ? $subcategory->name : '' ;
            //$q->measurementUnit = $measurementUnit != null ? $measurementUnit->name : null ;
            $q->productName = $product != null ? $product->name : '' ;
            $q->productPrice = $product != null ? $product->price : '' ;
            if($product):
                $measurementUnit = MeasurementUnit::find($product->measurement_id);
                $q->measurementUnit = $measurementUnit != null ? $measurementUnit->name : '';
                $q->productImage = $request->root() . '/' . $this->public_path . $product->image ;
            else:
                $q->measurementUnit = '';
                $q->productImage = '';
            endif;
            
        });

        /**
         * Return Data Array
         */

        return response()->json([
            'status' => 200,
            'data' => $offers
        ]);

    }

    public function details(Request $request){

        $rules = [
            'offerId' => 'required'  
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json(['status'=>400,'errors' => $validator->errors()->all()]);
        }

        $offer = Offer::where('id',$request->offerId)->where('status',1)->select('id', 'price' ,'category_id' ,'subcategory_id' , 'measurement_id' ,'product_id','amount', 'is_available','created_at')->first();

        if(!$offer){
            return response()->json([
                'status' => 400,
                'errors' => ['العرض غير موجود بالتطبيق'],
                'data' => []
            ]);
        }

        $category = Category::find($offer->category_id);
        $subcategory = Category::find($offer->subcategory_id);
        //$measurementUnit = MeasurementUnit::find($offer->measurement_id);
        $product = Product::find($offer->product_id);

        $offer->category = $category != null ? $category->name : '' ;
        $offer->subcategory = $subcategory != null ? $subcategory->name : '' ;
        //$offer->measurementUnit = $measurementUnit != null ? $measurementUnit->name : null ;
        $offer->productName = $product != null ? $product->name : '' ;
        $offer->productPrice = $product != null ? $product->price : '' ;
        if($product):
            $measurementUnit = MeasurementUnit::find($product->measurement_id);
            $offer->measurementUnit = $measurementUnit != null ? $measurementUnit->name : '';
            $offer->productImage = $request->root() . '/' . $this->public_path . $product->image ;
        else:
            $offer->measurementUnit = '';
            $offer->productImage = '';
        endif;

        return response()->json([
            'status' => 200,
            'data' => [$offer]
        ]);
    }

}
