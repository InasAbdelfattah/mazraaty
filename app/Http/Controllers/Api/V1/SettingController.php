<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use App\WorkDay;

class SettingController extends Controller
{

    public function index()
    {
        $settings = [
                'terms' => Setting::getBody('terms'),
                'about_app' => Setting::getBody('about_app_desc'),
                'support_phone' => Setting::getBody('support_phone'),
                'email' => Setting::getBody('email'),
                'fb' => Setting::getBody('fb'),
                'twitter' => Setting::getBody('twitter'),
                'instagram' => Setting::getBody('instagram'),
                'googlePlus' => Setting::getBody('googlePlus'),
                //'hotNos' => Setting::where('key', 'like', 'hot_no%')->select('body as number')->get(),
                'hotNos' => Setting::where('key', 'like', 'hot_no%')->pluck('body'),
                'workDays' => WorkDay::select('day','from','to')->get()
            ];

        $data =  json_decode(json_encode($settings),true);
        $data  =array_filter($data, function($value){
           return isset($value);
        });

        return response()->json([
            'status' => 200,
            'data' => [$data]
        ]);
    }

}
