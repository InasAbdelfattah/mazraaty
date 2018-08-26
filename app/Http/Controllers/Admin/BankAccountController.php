<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\BankAccount;
use UploadImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Auth;

class BankAccountController extends Controller
{
    
    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/banks/';
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

        $cities = BankAccount::get();

        return view('admin.banks.index')->with(compact('cities'));
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

        return view('admin.banks.create');
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

        //`name_ar`, `name_en`, `photo`, `account_no`, `status`

        $rules = [
            'name_ar' => 'required|min:3|max:50',
            'name_en' => 'required|min:3|max:50',
            'account_no' => 'required|min:3|max:1000',
            'image' => 'image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

           // return redirect()->back()->withErrors($validator)->withInput();

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($valErrors);
        }

        $bank = new BankAccount();
        $bank->name_ar = $request->name_ar;
        $bank->name_en = $request->name_en;
        $bank->account_no = $request->account_no;
        if ($request->hasFile('image')):
            $bank->photo = UploadImage::uploadImage($request, 'image', $this->public_path);
        else:
            $bank->photo = '';
        endif;

        $bank->status = $request->status ? $request->status : 1;

        $bank->save();

        session()->flash('success', 'تم إضافة الحساب البنكى بنجاح.');
        return redirect()->route('banks.index');

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

        $bank = BankAccount::findOrFail($id);

        return view('admin.banks.show')->with(compact('bank'));
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

        $bank = BankAccount::findOrFail($id);

        return view('admin.banks.edit')->with(compact('bank'));

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

        $bank = BankAccount::findOrFail($id);

        $rules = [
            'name_ar' => 'required|min:3|max:50',
            'name_en' => 'required|min:3|max:50',
            'account_no' => 'required|min:3|max:1000',
            'image' => 'image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

           // return redirect()->back()->withErrors($validator)->withInput();

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($valErrors);
        }

        $bank->name_ar = $request->name_ar;
        $bank->name_en = $request->name_en;
        $bank->account_no = $request->account_no;
        if ($request->hasFile('image')):
            $bank->photo = UploadImage::uploadImage($request, 'image', $this->public_path);
        else:
            $bank->photo = '';
        endif;

        $bank->status = $request->status ? $request->status : 1;

        $bank->save();

        session()->flash('success', 'تم تعديل بيانات الحساب البنكى بنجاح.');
        return redirect()->route('banks.index');
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

    public function activateBank(Request $request)
    {

        $model = BankAccount::findOrFail($request->id);
        //dd($request);

        if ($model) {

            if($model->status != $request->status) {
                if ($request->status == 1) {
                    $msg = 'تم تفعيل الحساب البنكى';
                } else {

                    $msg = 'تم تعطيل الحساب البنكى';
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
