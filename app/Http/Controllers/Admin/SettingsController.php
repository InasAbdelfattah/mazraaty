<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Company;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostsRequests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Auth;
use Config;
use Image;
use Session;
use UploadImage;

class SettingsController extends Controller
{

    /**
     * @var string
     * @ public variable to save path.
     */
    public $public_path;

    function __construct()
    {
        $this->public_path = 'files/settings/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        $settings = Setting::all();
        return view('admin.settings.setting')->withSettings($settings);
    }


    public function aboutus()
    {
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        $settings = Setting::all();
        return view('admin.settings.aboutus')->withSettings($settings);
    }
    
    public function site()
    {
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        $settings = Setting::all();
        return view('admin.settings.site')->withSettings($settings);
    }

    public function socialLinks()
    {
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        $settings = Setting::all();
        return view('admin.settings.socials')->withSettings($settings);
    }


    public function terms()
    {
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        $settings = Setting::all();
        return view('admin.settings.terms')->withSettings($settings);
    }
    
    public function eduTerms(){
        if (!Gate::allows('content_manage')) {
            return abort(401);
        }

        $settings = Setting::all();
        return view('admin.settings.edu_terms')->withSettings($settings);
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

        return view('admin.settings.index');
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
        
        foreach ($request->all() as $key => $value) {
            if ($key != '_token' && $key != 'about_app_image_old' && $key != 'site_image_old'):
                Setting::updateOrCreate(['key' => $key], ['body' => $value]);
            endif;
            
        }

        if ($request->hasFile('about_app_image')):
            Setting::updateOrCreate(['key' => 'about_app_image'], ['body' => UploadImage::uploadMainImage($request, 'about_app_image', $this->public_path)]);

            if ($request->about_app_image_old) {
                if (\File::exists(public_path($request->about_app_image_old))):
                    \File::delete(public_path($request->about_app_image_old));
                endif;
            }
        endif;
        
        if ($request->hasFile('site_image')):
            Setting::updateOrCreate(['key' => 'site_image'], ['body' => UploadImage::uploadMainImage($request, 'site_image', $this->public_path)]);

            if ($request->about_app_image_old) {
                if (\File::exists(public_path($request->site_image_old))):
                    \File::delete(public_path($request->site_image_old));
                endif;
            }
        endif;

        return redirect()->back()->with('success', 'تم الحفظ بنجاح');

    }


}