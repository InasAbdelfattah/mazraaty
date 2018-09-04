<?php

namespace App\Http\Controllers\Api\V1;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/categories/';
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
        
        $query = Category::where('status', 1)
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

        $categories = $query->select('id','name','image')->get();

        $categories->map(function ($q){

            $subcategories = Category::where('parent_id',$q->id)->get();
            $subcategories->map(function ($q) {
                $q->image= $request->root() . '/' . $this->public_path . $q->image ;
            });

            $q->image= $request->root() . '/' . $this->public_path . $q->image ;

            $q->subcategories= $subcategories;
            
        });

        /**
         * Return Data Array
         */

        return response()->json([
            'status' => true,
            'data' => $categories
        ]);

    }

}
