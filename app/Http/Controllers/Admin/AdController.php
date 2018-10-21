<?php

namespace App\Http\Controllers\Admin;

use App\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

use UploadImage;
use Validator;


class AdController extends Controller
{

    /**
     * @var Category
     */

    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/ads/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Gate::allows('ads_manage')) {
            return abort(401);
        }


        /**
         * Get all Categories
         */
        $ads = Ad::all();

        return view('admin.ads.index')
            ->with(compact('ads'));
    }
    
    public function getAds(){

        if (!Gate::allows('ads_manage')) {
            return abort(401);
        }

    	$ads = Ad::where('type',1)->paginate(10);

        return view('admin.ads.index')
            ->with(compact('ads'));
        }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!Gate::allows('ads_manage')) {
            return abort(401);
        }

        return view('admin.ads.create');
    }
    
    public function createAd()
    {
        if (!Gate::allows('ads_manage')) {
            return abort(401);
        }

        return view('admin.ads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!Gate::allows('ads_manage')) {
            return abort(401);
        }

        // Get Input
        $postData = [
            'image' => $request->image,
        ];

        // Declare Validation Rules.
        $valRules = [
            'image' => 'required|image',
        ];

        // Declare Validation Messages
        $valMessages = [
            'image.required' => 'هذا الحقل مطلوب',
            'image.image' => 'الصورة المرفوعة غير صالحة',
        ];

        // Validate Input
        $valResult = Validator::make($postData, $valRules, $valMessages);

        // Check Validate
        if ($valResult->passes()) {
            $model = new Ad;

            /**
             * @ Store Image With Image Intervention.
             */

            if ($request->hasFile('image')):
                //$model->image = uploadImage($request, 'image', $this->public_path );
                $model->image = uploadImage($request, 'image', $this->public_path , 600 , 300);
            endif;

            if ($model->save()) {
                session()->flash('success', 'تم الاضافة بنجاح');
                return redirect(route('ads.index'));
                return back();
            }
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('ads_manage')) {
            return abort(401);
        }

        $ad = Ad::findOrFail($id);
        return view('admin.ads.edit')->with(compact('ad'));
    }
    
    public function editAd($id)
    {
        if (!Gate::allows('ads_manage')) {
            return abort(401);
        }

        $ad = Ad::findOrFail($id);
        return view('admin.ads.edit')->with(compact('ad'));
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
        if (!Gate::allows('ads_manage')) {
            return abort(401);
        }

        $model = Ad::findOrFail($id);
      
        if ($request->hasFile('image')):
            //$model->image =  uploadImage($request, 'image', $this->public_path);
            $model->image = uploadImage($request, 'image', $this->public_path , 600 , 300);

        endif;


        if ($model->save()) {
            session()->flash('success', 'تم التعديل بنجاح');

            return redirect(route('ads.index'));
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
        if (!Gate::allows('ads_manage')) {
            return abort(401);
        }
        
        $model = Ad::findOrFail($id);
        $model->delete();
        
        return response()->json([
            'status' => true,
            'data' => [
                'id' => $id
            ]
        ]);
    }


    /**
     * Remove User from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function groupDelete(Request $request)
    {

        if (!Gate::allows('ads_manage')) {
            return abort(401);
        }

        $ids = $request->ids;
        if(count($ids)>0){
        foreach ($ids as $id) {
            $model = Ad::findOrFail($id);
            $model->delete();
        }
        }


        return response()->json([
            'status' => true,
            'data' => [
                'id' => $request->id
            ]
        ]);
    }

}
