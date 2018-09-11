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
        $this->public_path = 'files/offers/';
        $this->public_product_path = 'files/products/';
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
        $offers = $query->select('id','name','description' , 'price' ,'category_id' ,'subcategory_id' , 'measurement_id' ,'product_id','amount', 'is_available','created_at')->get();

        $offers->map(function ($q) use($request){

            $category = Category::find($q->category_id);
            $subcategory = Category::find($q->subcategory_id);
            $measurementUnit = MeasurementUnit::find($q->measurement_id);
            $product = Product::find($q->product_id);

            $q->category = $category != null ? $category->name : null ;
            $q->subcategory = $subcategory != null ? $subcategory->name : null ;
            $q->measurementUnit = $measurementUnit != null ? $measurementUnit->name : null ;
            $q->product = $product != null ? $product->name : null ;
            //$q->image= $request->root() . '/' . $this->public_path . $q->image ;
            
        });

        /**
         * Return Data Array
         */

        return response()->json([
            'status' => true,
            'data' => $offers
        ]);

    }

}
