<?php

namespace App\Http\Controllers\Api\V1;

use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
   
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
        
        $query = City::where('status', 1)
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

        $cities = $query->select('id','name')->get();

        /**
         * Return Data Array
         */

        return response()->json([
            'status' => true,
            'data' => $cities
        ]);

    }

}
