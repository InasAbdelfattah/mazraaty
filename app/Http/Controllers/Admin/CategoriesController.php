<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\CategoryArea;
use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

use UploadImage;


class CategoriesController extends Controller
{

    /**
     * @var Category
     */

    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/categories/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        /**
         * Get all Categories
         */
        $categories = Category::get();

        ## SHOW CATEGORIES LIST VIEW WITH SEND CATEGORIES DATA.
        return view('admin.categories.index')
            ->with(compact('categories'));
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

        //$areas = City::where('status',1)->get();
        $areas = City::all();
        return view('admin.categories.create',compact('areas'));
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

        $category = new Category;
        $category->name = $request->name;
        $category->status = $request->status;
        $category->parent_id = ($request->parent != null) ? $request->parent: 0;

        /**
         * @ Store Image With Image Intervention.
         */

        if ($request->hasFile('image')):
            $category->image = uploadImage($request, 'image', $this->public_path);
        endif;
        
        if ($category->save()) {
           
            session()->flash('success', 'لقد تم إضافة نوع بطاقة بنجاح' . $category->name_ar);
            return redirect(route('categories.index'));
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

        $category = Category::findOrFail($id);
        return view('admin.categories.show')->with(compact('category'));
  
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

        $category = Category::findOrFail($id);
       
        return view('admin.categories.edit')->with(compact('category'));
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

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->status = $request->status;
        $category->parent_id = ($request->parent != null) ? $request->parent: 0;

        /**
         * @ Store Image With Image Intervention.
         */

        if ($request->hasFile('image')):
            $category->image = uploadImage($request, 'image', $this->public_path);
        endif;


//        if ($category->save()) {
//            return response()->json([
//                'status' => true,
//                'message' => 'لقد تم إضافة نوع المنشأة بنجاح' . $category->name,
//                'data' => $category
//            ]);
//        }


        if ($category->save()) {
              
            session()->flash('success', 'لقد تم تعديل نوع البطاقة بنجاح' . $category->name_ar);

            return redirect(route('categories.index'));
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
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $model = Category::findOrFail($request->id);

        if ($model->products->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => "عفواً, لا يمكنك حذف القسم ($model->name) نظراً لوجود منتجات ملتحقة بها "
            ]);
        }

        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }

    /**
     * Custom Functions
     */


    function filter(Request $request)
    {

        $name = $request->keyName;

        $page = $request->pageSize;

        ## GET ALL CATEGORIES PARENTS
        $query = Category::select();

        if ($name != '') {
            $query->where('name_ar', 'like', "%$name%")->orWhere('name_en', 'like', "%$name%");
        }

        $categories = $query->paginate(($page) ?: 10);

        if ($name != '') {
            $categories->setPath('categories?name_ar=' . $name);
        } else {
            $categories->setPath('categories');
        }


        if ($request->ajax()) {
            return view('admin.categories.load', ['categories' => $categories])->render();
        }
        ## SHOW CATEGORIES LIST VIEW WITH SEND CATEGORIES DATA.
        return view('admin.categories.index')
            ->with(compact('categories'));
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
        foreach ($ids as $id) {

            $model = Category::findOrFail($id);
            
            if ($model->cards->count() > 0) {
                return response()->json([
                    'status' => false,
                    'message' => "عفواً, لا يمكنك حذف النوع ($model->name_ar) نظراً لوجود بطاقات ملتحقة بها "
                ]);
            }

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
        if (!Gate::allows('setting_manage')) {
            return abort(401);
        }

        $model = Category::findOrFail($request->id);

        if ($model->cards->count() > 0) {
                return response()->json([
                    'status' => false,
                    'message' => "عفواً, لا يمكنك حذف النوع ($model->name_ar) نظراً لوجود بطاقات ملتحقة بها "
                ]);
            }

        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }

    public function activateCategory(Request $request)
    {

        $model = Category::findOrFail($request->id);

        if ($model) {

            if($model->status != $request->status) {
                if ($request->status == 1) {
                    $msg = 'تم تفعيل نوع البطاقة';

                } elseif ($request->status == 0) {
                    if ($model->products->count() > 0) {
                        return response()->json([
                            'status' => false,
                            'message' => "عفواً, لا يمكنك تعطيل القسم ($model->name) نظراً لوجود منتجات ملتحقة بها "
                        ]);
                    }
                    $msg = 'تم تعطيل القسم';
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
