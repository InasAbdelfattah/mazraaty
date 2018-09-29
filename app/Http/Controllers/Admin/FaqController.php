<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Auth;
use UploadImage;

class FaqController extends Controller
{
    
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

        $faqs = Faq::get();

        return view('admin.faqs.index')->with(compact('faqs'));
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

        return view('admin.faqs.create');
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
            'question' => 'required|min:3|max:50',
            'answer' => 'required|min:3|max:1000',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

           // return redirect()->back()->withErrors($validator)->withInput();

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($valErrors);
        }

        $faq = new Faq;
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();

        session()->flash('success', 'لقد تم إضافة السؤال بنجاح.');
        return redirect()->route('faqs.index');

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

        $faq = Faq::findOrFail($id);

        return view('admin.faqs.show')->with(compact('faq'));
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

        $faq = Faq::findOrFail($id);

        return view('admin.faqs.edit')->with(compact('faq'));

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

        $faq = Faq::findOrFail($id);

        $rules = [
            'question' => 'required|min:3|max:50',
            'answer' => 'required|min:3|max:1000',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

           // return redirect()->back()->withErrors($validator)->withInput();

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($valErrors);
        }

        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();

        session()->flash('success', 'لقد تم تعديل السؤال بنجاح.');
        return redirect()->route('faqs.index');
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
        
        $model = Faq::findOrFail($id);

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

        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        $ids = $request->ids;
        foreach ($ids as $id) {
            $model = Faq::findOrFail($id);
            $model->delete();
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $request->id
            ]
        ]);
    }

}
