<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Order;
use App\User;
use App\Support;
use App\City;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{

    public function index()
    {
        $data['centersCount'] = 0;
        $data['usersCount'] = User::whereDoesntHave('roles')->where('is_admin',0)->get()->count();
        // $data['services_app'] = Service::get()->count();
        //$user_cards = UserCard::groupBy('user_id')->get();
        $data['completed_orders'] = 0;
        $data['areas'] = City::count();
        $data['cities'] = 0;
        $data['read_contacts'] = Support::where('is_read',1)->where('parent_id',0)->get()->count();
        $data['notread_contacts'] = Support::where('is_read',0)->where('parent_id',0)->get()->count();
        $data['categoriesCount'] = Category::all()->count();
        $data['unpaid_orders'] = 0;
        //$data['expired_user_cards'] = UserCard::where('to','<',date('Y-m-d'))->count();
        $data['expired_user_cards'] = 0;
        //user cards that seem to be expired.
        //$data['near_expired_user_cards'] = UserCard::where('to','<=',date('Y-m-d', strtotime('+7days')))->where('to','>',date('Y-m-d'))->count();
        $data['near_expired_user_cards'] = 0;
        return view('admin.home.index')->with(compact('data'));
        
    }
}
