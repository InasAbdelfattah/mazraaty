<?php

namespace App\Http\Controllers\Admin;

use App\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

use UploadImage;
use Validator;


class SliderController extends Controller
{

    /**
     * @var Category
     */

    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/sliders/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Gate::allows('content_manage')) {
            return abort(401);
        }


        /**
         * Get all Categories
         */
        $sliders = Slider::paginate(10);

        return view('admin.sliders.index')
            ->with(compact('sliders'));
    }
    
    public function getAds(){

        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

    	$sliders = Slider::where('type',1)->paginate(10);

        return view('admin.ads.index')
            ->with(compact('sliders'));
        }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        return view('admin.sliders.create');
    }
    
    public function createAd()
    {
        if (!Gate::allows('content_manage')) {
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

        if (!Gate::allows('content_manage')) {
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
            $model = new Slider;
            $model->type = $request->type ;

            /**
             * @ Store Image With Image Intervention.
             */

            if ($request->hasFile('image')):
                $model->image = UploadImage::uploadImage($request, 'image', $this->public_path);
            endif;

            if ($model->save()) {
                session()->flash('success', 'تم الاضافة بنجاح');
                return redirect(route('sliders.ads'));
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
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        $slider = Slider::findOrFail($id);
        return view('admin.sliders.edit')->with(compact('slider'));
    }
    
    public function editAd($id)
    {
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        $slider = Slider::findOrFail($id);
        return view('admin.ads.edit')->with(compact('slider'));
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
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        $model = Slider::findOrFail($id);
      
        if ($request->hasFile('image')):
            $model->image =  UploadImage::uploadImage($request, 'image', $this->public_path);
        endif;


        if ($model->save()) {
            session()->flash('success', 'تم التعديل بنجاح');

            //return redirect(route('sliders.index'));
            return back();
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

        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        if (!Gate::allows('users_delete')) {
            return abort(401);
        }

        $ids = $request->ids;
        if(count($ids)>0){
        foreach ($ids as $id) {
            $model = Slider::findOrFail($id);
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }
        
        $model = Slider::findOrFail($request->id);
        $model->delete();
        
        return response()->json([
            'status' => true,
            'data' => [
                'id' => $request->id
            ]
        ]);
    }


}
