<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\City;
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
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $cities = City::get();

        return view('admin.cities.index')->with(compact('cities'));
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
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        // Get Input
        $postData = [
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,
        ];

        // Declare Validation Rules.
        $valRules = [
            'name_ar' => 'required|min:3|max:50',
            'name_en' => 'required|min:3|max:50',
            'description_ar' => 'required|min:3|max:1000',
            'description_en' => 'required|min:3|max:1000',
        ];

        // Declare Validation Messages
        $valMessages = [
            'name_ar.required' => 'اسم لمنطقة الرئيسية عربى مطلوب',
            'name_en.required' => 'اسم لمنطقة الرئيسية انجليزى مطلوب',
            'description_ar.required' => 'وصف المنطقة الرئيسية عربى مطلوب',
            'description_en.required' => 'وصف المنطقة الرئيسية انجليزى مطلوب',
            'name_ar.max:50' => 'اسم المنطقة الفرعية عربى يجب الا يزيد عن 50 حرف',
            'name_en.max:50' => 'اسم المنطقة الفرعية انجليزىيجب الا يزيد عن 50 حرف',
            'description_ar.max:1000' => 'وصف المنطقة الفرعية عربى الا يزيد عن 1000 حرف',
            'description_en.max:1000' => 'وصف المنطقة الفرعية انجليزى الا يزيد عن 1000 حرف',
        ];

        // Validate Input
        $valResult = Validator::make($postData, $valRules, $valMessages);

        // Check Validate
        if ($valResult->passes()) {

            $city = new City;
            $city->name_ar = $request->name_ar;
            $city->name_en = $request->name_en;
            $city->description_ar = $request->description_ar;
            $city->description_en = $request->description_en;
            if ($request->hasFile('image')):
                $city->image = UploadImage::uploadImage($request, 'image', $this->public_path);
            else:
                $city->image = '';
            endif;
            

            $city->status = $request->status;

            $city->save();

            session()->flash('success', 'لقد تم إضافة المنطقة الرئيسية.');
            return redirect()->route('cities.index');


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
        if (!Gate::allows('setting_manage')) {
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
        if (!Gate::allows('cities_manage')) {
            return abort(401);
        }

        $city = City::findOrFail($id);

        // Get Input
        $postData = [
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,
        ];

        // Declare Validation Rules.
        $valRules = [
            'name_ar' => 'required',
            'name_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
        ];

        // Declare Validation Messages
        $valMessages = [
            'name_ar.required' => 'اسم لمنطقة الرئيسية عربى مطلوب',
            'name_en.required' => 'اسم لمنطقة الرئيسية انجليزى مطلوب',
            'description_ar.required' => 'وصف المنطقة الرئيسية عربى مطلوب',
            'description_en.required' => 'وصف المنطقة الرئيسية انجليزى مطلوب',
        ];

        // Validate Input
        $valResult = Validator::make($postData, $valRules, $valMessages);

        // Check Validate
        if ($valResult->passes()) {


            $city->name_ar = $request->name_ar;
            $city->name_en = $request->name_en;
            $city->description_ar = $request->description_ar;
            $city->description_en = $request->description_en;

            $city->status = $request->status;
            
            if ($request->hasFile('image')):
                $city->image = UploadImage::uploadImage($request, 'image', $this->public_path);
            endif;

            $city->save();

            session()->flash('success', 'لقد تم تعديل المنطقة الرئيسية بنجاح.');
            return redirect()->route('cities.index');


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
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }
        
        $model = City::findOrFail($request->id);

        if ($model->centers->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => "عفواً, لا يمكنك حذف المنطقة الرئيسية لوجود مراكز بها"
            ]);
        }

        if ($model->districts->count() > 0) {
            foreach($model->districts as $district){
                $district->delete();
            }
        }

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
                    $msg = 'تم تفعيل المنطقة الرئيسية';
                } else {

                    if ($model->districts->count() > 0) {
                        return response()->json([
                            'status' => false,
                            'message' => "عفواً, لا يمكنك تعطيل المنطقة الرئيسية ($model->name_ar) نظراً لوجود مناطق فرعية ملتحقة بها "
                        ]);
                    }

                    $msg = 'تم تعطيل المنطقة الرئيسية';
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
