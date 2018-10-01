<?php

namespace App\Http\Controllers\Api\V1;

use App\City;
use App\User;
use App\Vote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CityController extends Controller
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
            'status' => 200,
            'data' => $cities
        ]);

    }

    public function getAllCities(Request $request)
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
        
        $query = City::orderBy('created_at', 'desc')
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
            'status' => 200,
            'data' => $cities
        ]);

    }

    

    public function voteForCity(Request $request){
        
        $user = auth()->user();
        
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'مستخدم غير مسجل بالتطبيق',
                'data' => []
            ]);
        }

        $rules = [
            'cityId' => 'required',
            ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            //$error_arr = validateRules($validator->errors(), $rules);

            return response()->json(['status'=>400,'errors' => $validator->errors()->all()]);
        }

        $city = City::where('id',$request->cityId)->first();
        //$city = City::where('name','like','%'.$request->cityId.'%')->first();

        if(!$city){
            return response()->json([
                'status' => 400,
                'message' => 'مدينة غير مسجلة فى لوحة التحكم',
                'errors' => ['مدينة غير مسجلة فى لوحة التحكم'],
                'data' => []
            ]);
        }

        // $city = City::where('name','like','%'.$request->city.'%')->where('status',1)->first();

        if($city->status == 1){
            return response()->json([
                'status' => 400,
                'message' => 'هذه المدينة متاحة فى التطبيق',
                'errors' => ['هذه المدينة متاحة فى التطبيق'],
                'data' => []
            ]);
        }

        // $model = Vote::where('city','like','%'.$request->city.'%')->where('user_id',$user->id)->first();
        $model = Vote::where('city_id',$request->cityId)->where('user_id',$user->id)->first();

        if($model){
            return response()->json([
                'status' => 400,
                'message' => 'تم التصويت من قبل',
                'errors' => ['تم التصويت من قبل'],
                'data' => []
            ]);
        }

        $newModel = new Vote();
        $newModel->user_id = $user->id;
        $newModel->city_id = $request->cityId;
        $newModel->city = '';
        $newModel->save();

        return response()->json([
            'status' => 200,
            'message' => 'تم التصويت للمدينة بنجاح',
            'data' => []
        ]);
    }

}
