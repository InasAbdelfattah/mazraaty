<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Product;
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

        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        /**
         * Get all Categories
         */
        $categories = Category::where('parent_id',0)->get();
        $type = 'cats';
        $cat = Category::where('parent_id',0)->get();
        $subcat = Category::where('parent_id','!=',0)->get();

        ## SHOW CATEGORIES LIST VIEW WITH SEND CATEGORIES DATA.
        return view('admin.categories.index')
            ->with(compact('categories' , 'type','cat' , 'subcat'));
    }

    public function getSubCategories(){
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        /**
         * Get all Categories
         */
        $categories = Category::where('parent_id','!=',0)->get();
        $cat = Category::where('parent_id',0)->get();
        $subcat = Category::where('parent_id','!=',0)->get();

        $type = 'cats';
        ## SHOW CATEGORIES LIST VIEW WITH SEND CATEGORIES DATA.
        return view('admin.categories.subcats')
            ->with(compact('categories','type' , 'cat' , 'subcat'));
    }

    public function search(Request $request)
    {
        
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        $categories = [] ;

        $query = Category::select();

        if($request->id):
            $query->where('id',$request->id);
        endif;

        if($request->parent_id != null):
            $query->where('parent_id',$request->parent_id);
        else:
            $query->where('parent_id','!=',0);
        endif;


        if($request->status != null):
            $status = (int)$request->status;
            $query->where('status',$status);
        endif;
        
        $categories = $query->orderBy('id','DESC')->get();
        $type = 'cats';

        $cat = Category::where('parent_id',0)->get();
        $subcat = Category::where('parent_id','!=',0)->get();
        $type='search';
        
        if($request->parent_id != null && $request->parent_id ==0):
            return view('admin.categories.index',compact('categories','cat','subcat','type'));
        else:
            return view('admin.categories.subcats',compact('categories','cat','subcat','type'));
        endif;



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }
        $type = $request->type;
        $cates = Category::where('parent_id',0)->get(); 
        return view('admin.categories.create',compact('type' , 'cates'));
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

        $category = new Category;
        $category->name = $request->name;
        $category->status = $request->status;
        $category->parent_id = ($request->parent_id != null) ? $request->parent_id: 0;

        /**
         * @ Store Image With Image Intervention.
         */

        if ($request->hasFile('image')):
            $category->image = uploadImage($request, 'image', $this->public_path);
        endif;
        
        if ($category->save()) {
           
            session()->flash('success', 'لقد تم إضافة قسم بنجاح' . $category->name);
            if($category->parent_id == 0):
                return redirect(route('categories.index'));
            else:
                return redirect(route('subcategories'));
            endif;
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
        if (!Gate::allows('settings_manage')) {
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
    public function edit($id , Request $request)
    {
        if (!Gate::allows('settings_manage')) {
            return abort(401);
        }

        $category = Category::findOrFail($id);

        $type = $request->type;
        $cates = Category::where('parent_id',0)->get();        
        return view('admin.categories.edit')->with(compact('category' , 'cates' , 'type'));
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

        if($request->status == 0){
                
                if($category->parent_id == 0):
                	//if ($category->products->count() > 0) :
                	$products = Product::where('category_id',$category->id)->where('status',1)->get();
                	if ($products->count() > 0) :
	                    return redirect()->route('categories.index')->with('error', 'لا يمكنك تعطيل القسم الرئيسى لاستخدامه فى منتجات مفعلة');
	                endif;
                else:
                	$products = Product::where('subcategory_id',$category->id)->where('status',1)->get();
                	if ($products->count() > 0) :
	                    return redirect()->route('subcategories')->with('error', 'لا يمكنك تعطيل القسم الفرعى لاستخدامه فى منتجات مفعلة');
	                endif;
                endif;
        }

        $category->name = $request->name;

        $category->status = $request->status;
        $category->parent_id = ($request->parent_id != null) ? $request->parent_id: 0;

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
              
            session()->flash('success', 'لقد تم تعديل القسم بنجاح ');

            if($category->parent_id == 0):
                return redirect(route('categories.index'));
            else:
                return redirect(route('subcategories'));
            endif;
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
        if (!Gate::allows('settings_manage')) {
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

        if (!Gate::allows('settings_manage')) {
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
        if (!Gate::allows('settings_manage')) {
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
                    $msg = 'تم تفعيل القسم';

                } elseif ($request->status == 0) {
                	if($model->parent_id == 0):
	                	//if ($category->products->count() > 0) :
	                	$products = Product::where('category_id',$model->id)->where('status',1)->get();
	                	if ($products->count() > 0) :
		                    return response()->json([
                            'status' => false,
                            'message' => "عفواً, لا يمكنك تعطيل القسم الرئيسى : ($model->name) نظراً لوجود منتجات ملتحقة به "
                        ]);
		                endif;
	                else:
	                	$products = Product::where('subcategory_id',$model->id)->where('status',1)->get();
	                	if ($products->count() > 0) :
		                    return response()->json([
                            'status' => false,
                            'message' => "عفواً, لا يمكنك تعطيل القسم الفرعى :  ($model->name) نظراً لوجود منتجات ملتحقة به "
                        ]);
		                endif;
	                endif;
                
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
