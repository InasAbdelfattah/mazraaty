<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Gate;
use App\Card;
use App\CardService;
use App\Category;
use App\UserCard;
use UploadImage;


class CardsController extends Controller
{

    /**
     * @var Category
     */

    public $public_path;

    public function __construct()
    {
        $this->public_path = 'files/cards/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }

        /**
         * Get all Categories
         */
        $cards = Card::with('cardServices')->get();

        ## SHOW CATEGORIES LIST VIEW WITH SEND CATEGORIES DATA.
        return view('admin.cards.index',compact('cards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }
        $categories = Category::where('status' , 1)->get();
        return view('admin.cards.create' , compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//dd($request->service);
        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }
//`name_ar`, `name_en`, `image`, `category_id`, `status`,
// `benifits_en`, `benifits_ar`, `about_card_ar`, `about_card_en`, `price`, `created_by
        
        // Declare Validation Rules.
        $rules = [
            'name_ar' => 'required|min:3|max:50',
            'name_en' => 'required|min:3|max:50',
            'benifits_ar' => 'required|min:3|max:10000',
            'benifits_en' => 'required|min:3|max:10000',
            'about_card_ar' => 'required|min:3|max:10000',
            'about_card_en' => 'required|min:3|max:10000',
            'expiration' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required',
            'image' => 'image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            //dd($validator->errors());

           // return redirect()->back()->withErrors($validator)->withInput();

            $valErrors = $validator->messages();
            return redirect()->back()->withInput()
                ->withErrors($validator->errors());
        }
            
        $card = new Card;
        $card->name_ar = $request->name_ar;
        $card->name_en = $request->name_en;
        $card->expiration = $request->expiration;
        $card->benifits_ar = $request->benifits_ar;
        $card->benifits_en = $request->benifits_en;
        $card->about_card_ar = $request->about_card_ar;
        $card->about_card_en = $request->about_card_en;
        $card->status = $request->status;
        $card->price = $request->price ? $request->price : 0;
        $card->category_id = $request->category_id ? $request->category_id : 1;
        $card->created_by = auth()->user()->id;
        if ($request->hasFile('image')):
            $card->image = UploadImage::uploadImage($request, 'image', $this->public_path);
        else:
            $card->image = '';
        endif;
        $card->save();
        
        if($request->has('service') && $request->service != ''){
            //dd($request->service);
            //$newProduct->others = json_encode($request->others) ;
            if(count($request->service) > 0){
                foreach($request->service as $s){
                   // dd($s['name_ar']);

                    $service = new CardService();
                    $service->name_ar = $s['name_ar'];
                    $service->name_en = $s['name_en'];
                    $service->status = 1;
                    $service->card_id = $card->id;
                    $service->save();
                }
            }

        }

        /**
         * @ Store Image With Image Intervention.
         */

        if ($card->id) {
            $title = 'بيع بطاقات';
            $body = 'تم اضافة بطاقة جديدة وهى '.$card->name_ar;
            
            $notif = sendOneSignalNotif('all' ,null , $title , $body ,null , 'card');
            
            //dd($notif);
            
            session()->flash('success', 'لقد تم إضافة بطاقة بنجاح' . $card->name_ar);
            return redirect(route('cards.index'));
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
        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }

        $card = Card::with('cardServices')->findOrFail($id);
        $card->category = $card->category()->first() ? $card->category()->first()->name_ar :null;

        return view('admin.cards.show')->with(compact('card'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }
        $categories = Category::where('status' , 1)->get();

        $card = Card::with('cardServices')->findOrFail($id);
        $card->category = $card->category()->first() ? $card->category()->first()->name_ar :null;

        return view('admin.cards.edit')->with(compact('card' , 'categories'));
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
        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }

        $card = Card::findOrFail($id);
        $card->name_ar = $request->name_ar;
        $card->name_en = $request->name_en;
        $card->expiration = $request->expiration;
        $card->benifits_ar = $request->benifits_ar;
        $card->benifits_en = $request->benifits_en;
        $card->about_card_ar = $request->about_card_ar;
        $card->about_card_en = $request->about_card_en;
        $card->status = $request->status;
        $card->price = $request->price ? $request->price : 0;
        $card->category_id = $request->category_id ? $request->category_id : 1;
        $card->created_by = auth()->user()->id;
        if ($request->hasFile('image')):
            $card->image = UploadImage::uploadImage($request, 'image', $this->public_path);
        endif;
        $card->save();
        if($request->has('service') && $request->service != ''){
            //dd($request->service);
            //$newProduct->others = json_encode($request->others) ;
            if(count($request->service) > 0){
                $services = CardService::where('card_id',$card->id)->get();
                if(count($services) > 0){
                    foreach($services as $service){
                        $service->delete();
                    }
                }
                foreach($request->service as $s){
                   // dd($s['name_ar']);

                    $service = new CardService();
                    $service->name_ar = $s['name_ar'];
                    $service->name_en = $s['name_en'];
                    $service->status = 1;
                    $service->card_id = $card->id;
                    $service->save();
                }
            }

        }

        if ($card->save()) {
            session()->flash('success', 'لقد تم تعديل نوع البطاقة بنجاح' . $card->name_ar);

            return redirect(route('cards.index'));
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

    public function activateCard(Request $request)
    {

        $model = Card::findOrFail($request->id);
        //dd($request);

//        if ($model->companies->count() > 0) {
//            return response()->json([
//                'status' => false,
//                'message' => "عفواً, لا يمكنك حذف النوع ($model->name) نظراً لوجود مراكز ملتحقة بهذا النوع"
//            ]);
//        }

        if ($model) {
            
            $user_cards = UserCard::where('card_id',$model->id)->where('to','>=',date('Y-m-d'))->count();
            if($user_cards > 0 && $request->status == 0){
                return response()->json([
                    'status' => false,
                    'message' => 'لا يمكن تعطيل البطاقة لوجود مستخدمين مشترين للبطاقة',
                ]);
            }

            if($model->status != $request->status) {
                if ($request->status == 1) {
                    $msg = 'تم تفعيل البطاقة';
                } else {
                    $msg = 'تم تعطيل البطاقة';
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


    /**
     * Remove User from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function groupDelete(Request $request)
    {

        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }

        $ids = $request->ids;
        foreach ($ids as $id) {

            $model = Card::findOrFail($id);

            if ($model->cardServices->count() > 0) {
                return response()->json([
                    'status' => false,
                    'message' => "عفواً, لا يمكنك حذف النوع ($model->name) نظراً لوجود مراكز ملتحقة بهذا النوع"
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
        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }

        $model = Card::findOrFail($request->id);

//        if ($model->companies->count() > 0) {
//            return response()->json([
//                'status' => false,
//                'message' => "عفواً, لا يمكنك حذف النوع ($model->name) نظراً لوجود مراكز ملتحقة بهذا النوع"
//            ]);
//        }

        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }

    public function deleteService(Request $request)
    {
        if (!Gate::allows('cards_manage')) {
            return abort(401);
        }

        $model = CardService::findOrFail($request->id);

        if ($model->delete()) {
            return response()->json([
                'status' => true,
                'data' => $model->id
            ]);
        }
    }
}
