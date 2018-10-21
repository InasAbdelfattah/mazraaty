<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\City;
use App\Vote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Auth;
use UploadImage;

class CitiesController extends Controller
{
    
    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/areas/';
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        $cities = City::get();
        $list = City::all();
        $type = 'cities';

        return view('admin.cities.index')->with(compact('cities','type','list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        return view('admin.cities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        $rules = [
            'name' => 'required|min:3|max:50|unique:cities,name',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

           // return redirect()->back()->withErrors($validator)->withInput();

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($valErrors);
        }

            $city = new City;
            $city->name = $request->name;
            $city->status = $request->status;
            $city->save();

            session()->flash('success', 'لقد تم إضافة المدينة بنجاح.');
            return redirect()->route('cities.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        $city = City::findOrFail($id);

        return view('admin.cities.show')->with(compact('city'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        $city = City::findOrFail($id);

        return view('admin.cities.edit')->with(compact('city'));

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
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        $city = City::findOrFail($id);

        $rules = [
            'name' => 'required|min:3|max:50|unique:cities,name,'.$city->id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

           // return redirect()->back()->withErrors($validator)->withInput();

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($valErrors);
        }

        $city->name = $request->name;
        $city->status = $request->status;
        $city->save();

        session()->flash('success', 'لقد تم تعديل المدينة بنجاح.');
        return redirect()->route('cities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }
        
        $model = City::findOrFail($id);

        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
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

        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $ids = $request->ids;

        $arrsCannotDelete = [];
        foreach ($ids as $id) {
            $model = City::findOrFail($id);

            if ($model->centers->count() > 0) {
                $arrsCannotDelete[] = $model->name;
            } else {
                $model->delete();
            }
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $request->id
            ],
            'message' => $arrsCannotDelete
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
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }
        
        $model = City::findOrFail($request->id);

        // if ($model->centers->count() > 0) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => "عفواً, لا يمكنك حذف المنطقة الرئيسية لوجود مراكز بها"
        //     ]);
        // }

        // if ($model->districts->count() > 0) {
        //     foreach($model->districts as $district){
        //         $district->delete();
        //     }
        // }

        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }

    public function activateArea(Request $request)
    {

        $model = City::findOrFail($request->id);
        //dd($request);

        if ($model) {

            if($model->status != $request->status) {
                if ($request->status == 1) {
                    $msg = 'تم تفعيل المدينة';
                } else {
                    $msg = 'تم تعطيل المدينة';
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

    public function search(Request $request)
    {
        
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        $cities = [] ;

        $query = City::select();
        if($request->city):
            $query->where('id',$request->city);
        endif;


        if($request->status != null):
            $status = (int)$request->status;
            $query->where('status',$status);
        endif;
        
        $cities = $query->orderBy('id','DESC')->get();

        $list = City::all();
        $type='search';
        
        return view('admin.cities.index')->with(compact('cities','type','list'));


    }

    public function getCityVotes(Request $request)
    {

        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        $cities = Vote::join('users','votes.user_id','users.id')->join('cities','votes.city_id','cities.id')->distinct('votes.city_id')->select('votes.city_id as city_id','cities.name as city_name')->get();

        $cities->map(function ($q){
            $q->vote_count = Vote::where('city_id',$q->city_id)->distinct('user_id')->count('user_id');
            
        });

        $type = 'votes';

        return view('admin.cities.votes',compact('cities' , 'type'));
    }




}
