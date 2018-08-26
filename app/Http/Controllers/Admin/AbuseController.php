<?php

namespace App\Http\Controllers\Admin;

use App\Abuse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;


class AbuseController extends Controller
{
    public function index()
    {
        if (!Gate::allows('abuses_manage')) {
            return abort(401);
        }

        $abuses = Abuse::join('users','abuses.user_id','users.id')->join('companies','abuses.company_id','companies.id')->select('abuses.*','users.id as user_id' , 'users.name as username' , 'users.phone as user_phone' , 'companies.id as company_id' , 'companies.nameAr as company_name')->get();
        // $abuses->map(function ($q)  {
        //     $q->name = $q->{'name:ar'};
            

        // });

        return view('admin.abuses.index',compact('abuses'));
        
    }

        /**
     * Remove User from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function groupDelete(Request $request)
    {

        if (!Gate::allows('abuses_manage')) {
            return abort(401);
        }

        $ids = $request->ids;
        foreach ($ids as $id) {
            $model = Abuse::findOrFail($id);
            $model->delete();
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $request->id
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if (!Gate::allows('abuses_manage')) {
            return abort(401);
        }

        $model = Abuse::findOrFail($request->id);

        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }

    public function adoptAbuse(Request $request)
    {
        if (!Gate::allows('abuses_manage')) {
            return abort(401);
        }

        $model = Abuse::find($request->id);

        if(!$model){
            return response()->json([
                'status' => false,
                'message' => 'Fail',
            ]);
        }

        $model->is_adopt = $model->is_adopt == 1 ? 0 : 1;
        $model->save();

        return response()->json([
            'status' => true,
            'adopt' => $model->is_adopt,
            'data' => [
                'id' => $request->id ,
            ]
        ]);
    }
}
