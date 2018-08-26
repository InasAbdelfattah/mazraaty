<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\City;
use App\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Auth;

class DistrictsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $districts = District::join('areas','cities.area_id','areas.id')->where('areas.status',1)->select('cities.*')->get();

        return view('admin.districts.index')->with(compact('districts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $cities = City::where('status',1)->get();
        return view('admin.districts.create' , compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        // Get Input
        $postData = [
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,
            'area_id' => $request->city_id,
        ];

        // Declare Validation Rules.
        $valRules = [
            'name_ar' => 'required|min:3|max:50',
            'name_en' => 'required|min:3|max:50',
            'description_ar' => 'required|min:3|max:1000',
            'description_en' => 'required|min:3|max:1000',
            'area_id' => 'required',
        ];

        // Declare Validation Messages
        $valMessages = [
            'name_ar.required' => 'اسم المنطقة الفرعية عربى مطلوب',
            'name_en.required' => 'اسم المنطقة الفرعية انجليزى مطلوب',
            'description_ar.required' => 'وصف المنطقة الفرعية عربى مطلوب',
            'description_en.required' => 'وصف المنطقة الفرعية انجليزى مطلوب',
            'name_ar.max:50' => 'اسم المنطقة الفرعية عربى يجب الا يزيد عن 50 حرف',
            'name_en.max:50' => 'اسم المنطقة الفرعية انجليزىيجب الا يزيد عن 50 حرف',
            'description_ar.max:1000' => 'وصف المنطقة الفرعية عربى الا يزيد عن 1000 حرف',
            'description_en.max:1000' => 'وصف المنطقة الفرعية انجليزى الا يزيد عن 1000 حرف',
            'area_id.required' => 'المنطقة الرئيسية مطلوب',
        ];

        // Validate Input
        $valResult = Validator::make($postData, $valRules, $valMessages);

        // Check Validate
        if ($valResult->passes()) {

            $district = new District;
            $district->name_ar = $request->name_ar;
            $district->name_en = $request->name_en;
            $district->description_ar = $request->description_ar;
            $district->description_en = $request->description_en;
            $district->image = '';
            //$district->status = 1;
            $district->status = $request->status;
            $district->area_id = $request->city_id;

            $district->save();

            session()->flash('success', 'لقد تم إضافة المنطقة الفرعية بنجاح.');
            return redirect()->route('districts.index');


        } else {
            // Grab Messages From Validator
            $valErrors = $valResult->messages();
            // Error, Redirect To User Edit
            return redirect()->back()->withInput()
                ->withErrors($valErrors);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $city = District::findOrFail($id);

        return view('admin.districts.show')->with(compact('city'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $district = District::findOrFail($id);
        $cities = City::where('status',1)->get();

        return view('admin.districts.edit')->with(compact('district' , 'cities'));

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
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $district = District::findOrFail($id);

        // Get Input
        $postData = [
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,
            'area_id' => $request->city_id,
        ];

        // Declare Validation Rules.
        $valRules = [
            'name_ar' => 'required',
            'name_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'area_id' => 'required',
        ];

        // Declare Validation Messages
        $valMessages = [
            'name_ar.required' => 'اسم المنطقة الفرعية عربى مطلوب',
            'name_en.required' => 'اسم المنطقة الفرعية انجليزى مطلوب',
            'description_ar.required' => 'وصف المنطقة الفرعية عربى مطلوب',
            'description_en.required' => 'وصف المنطقة الفرعية انجليزى مطلوب',
            'area_id.required' => 'المنطقة الرئيسية مطلوب',
        ];

        // Validate Input
        $valResult = Validator::make($postData, $valRules, $valMessages);

        // Check Validate
        if ($valResult->passes()) {


            $district->name_ar = $request->name_ar;
            $district->name_en = $request->name_en;
            $district->description_ar = $request->description_ar;
            $district->description_en = $request->description_en;
            $district->area_id = $request->city_id;
            $district->status = $request->status;

            //$district->created_by = Auth::user()->id;

            $district->save();

            session()->flash('success', 'لقد تم تعديل الحى بنجاح.');
            return redirect()->route('districts.index');


        } else {
            // Grab Messages From Validator
            $valErrors = $valResult->messages();
            // Error, Redirect To User Edit
            return redirect()->back()->withInput()
                ->withErrors($valErrors);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
            $model = District::findOrFail($id);

            // if ($model->services->count() > 0) {
            //     $arrsCannotDelete[] = $model->name;
            // } else {
            //     $model->delete();
            // }
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
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }
        
        $model = District::findOrFail($request->id);

        // if ($model->centers->count() > 0) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => "عفواً, لا يمكنك حذف المدينة لوجود مراكز بها"
        //     ]);
        // }

        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }

    public function activateCity(Request $request)
    {

        $model = District::findOrFail($request->id);
        //dd($request);

//        if ($model->companies->count() > 0) {
//            return response()->json([
//                'status' => false,
//                'message' => "عفواً, لا يمكنك حذف النوع ($model->name) نظراً لوجود مراكز ملتحقة بهذا النوع"
//            ]);
//        }

        if ($model) {

            if($model->status != $request->status) {
                if ($request->status == 1) {
                    $msg = 'تم تفعيل المنطقة الفرعية';
                } else {

                    if ($model->centers->count() > 0) {
                        return response()->json([
                            'status' => false,
                            'message' => "عفواً, لا يمكنك تعطيل المنطقة الفرعية ($model->name_ar) نظراً لوجود مراكز ملتحقة بها "
                        ]);
                    }

                    $msg = 'تم تعطيل المنطقة الفرعية';
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


}
