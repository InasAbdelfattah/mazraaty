<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\MeasurementUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Auth;
use UploadImage;

class MeasurementUnitController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('measurements_manage')) {
            return abort(401);
        }

        $measurements = MeasurementUnit::get();

        return view('admin.measurements.index')->with(compact('measurements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('measurements_manage')) {
            return abort(401);
        }

        return view('admin.measurements.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('measurements_manage')) {
            return abort(401);
        }

        $rules = [
            'name' => 'required|min:3|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

           // return redirect()->back()->withErrors($validator)->withInput();

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($valErrors);
        }
            $measurement = new MeasurementUnit;
            $measurement->name = $request->name;
            $measurement->status = $request->status;
            $measurement->save();

            session()->flash('success', 'لقد تم إضافة وحدة قياس بنجاح.');
            return redirect()->route('measurementUnits.index');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('measurements_manage')) {
            return abort(401);
        }

        $measurement = MeasurementUnit::findOrFail($id);

        return view('admin.measurements.edit')->with(compact('measurement'));

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
        if (!Gate::allows('measurements_manage')) {
            return abort(401);
        }

        $measurement = MeasurementUnit::findOrFail($id);
        $rules = [
            'name' => 'required|min:3|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

           // return redirect()->back()->withErrors($validator)->withInput();

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($valErrors);
        }

        $measurement->name = $request->name;
        $measurement->status = $request->status;
        $measurement->save();

        session()->flash('success', 'لقد تم تعديل المدينة بنجاح.');
        return redirect()->route('measurementUnits.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }
        
        $model = MeasurementUnit::findOrFail($id);

        // if ($model->centers->count() > 0) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => "عفواً, لا يمكنك حذف المنطقة الرئيسية لوجود مراكز بها"
        //     ]);
        // }

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

        if (!Gate::allows('measurements_manage')) {
            return abort(401);
        }

        $ids = $request->ids;

        $arrsCannotDelete = [];
        foreach ($ids as $id) {
            $model = MeasurementUnit::findOrFail($id);

            // if ($model->centers->count() > 0) {
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

    public function activateMeasurement(Request $request)
    {

        $model = MeasurementUnit::findOrFail($request->id);

        if ($model) {

            if($model->status != $request->status) {
                if ($request->status == 1) {
                    $msg = 'تم تفعيل وحدة القياس';
                } else {

                    $

                    $msg = 'تم تعطيل وحدة القياس';
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
