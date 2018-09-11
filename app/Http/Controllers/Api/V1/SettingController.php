<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
 use App\workDay;

class SettingController extends Controller
{

    
    
    public function index()
    {
        return response()->json([
            'status' => 'true',
            'data' => [
                'terms' => Setting::getBody('terms'),
                'about_app' => Setting::getBody('about_app_desc'),
                'support_phone' => Setting::getBody('support_phone'),
                'email' => Setting::getBody('email'),
                'fb' => Setting::getBody('fb'),
                'twitter' => Setting::getBody('twitter'),
                'instagram' => Setting::getBody('instagram'),
                'googlePlus' => Setting::getBody('googlePlus'),
                'hotNos' => Setting::where('key', 'like', 'hot_no%')->select('body as number')->get(),
                'workdays' => workDay::select('day','from','to')->get()
            ]
        ]);
    }


    public function generalInfo()
    {
        //app()->setLocale($request->lang);

        return response()->json([
            'status' => 'true',
            'data' => [
                'terms' => Setting::getBody('terms_'.app()->getlocale()),
                'provider_terms' => Setting::getBody('providerTerms_'.app()->getlocale()),
                'about_app_desc' => Setting::getBody('about_app_desc_'.app()->getlocale()),
                'facebook' => Setting::getBody('facebook'),
                'twitter' => Setting::getBody('twitter'),
                'instagram' => Setting::getBody('instagram'),
                'googlePlus' => Setting::getBody('googlePlus')
            ]
        ]);
    }

}
