<?php

namespace App\Http\Controllers\Api\V1;

use App\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AdController extends Controller
{

    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/ads/';
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
        
        $query = Ad::orderBy('created_at', 'desc')
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

        $ads = $query->select('id','image')->get();

        $ads->map(function ($q){

            $q->image= Request()->root() . '/' . $this->public_path . $q->image ;
            
        });

        /**
         * Return Data Array
         */

        return response()->json([
            'status' => 200,
            'data' => $ads
        ]);

    }

}
