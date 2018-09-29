<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Order;
use App\User;
use App\Offer;
use App\Product;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{

    public function index()
    {
        $data['usersCount'] = User::whereDoesntHave('roles')->where('is_admin',0)->get()->count();
        $data['activeproducts'] = Product::where('status',1)->count();
        $data['inactiveproducts'] = Product::where('status',0)->count();
        $data['products'] = Product::count();
        $data['availableOffers'] = Offer::where('is_available',1)->count();
        $data['inavailableOffers'] = Offer::where('is_available',0)->count();
        $data['categoriesCount'] = Category::where('parent_id',0)->count();
        $data['subcategoriesCount'] = Category::where('parent_id','!=',0)->count();
        $data['waiting_orders'] = Order::where('status',0)->count();
        $data['accepted_orders'] = Order::whereIn('status',[1,3])->count();
        $data['refused_orders'] = Order::where('status',2)->count();
        $data['excuted_orders'] = 0;
        $data['votes'] = 0;
        return view('admin.home.index')->with(compact('data'));
        
    }
}
