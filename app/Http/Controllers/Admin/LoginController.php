<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\UserLogin;
use App\User;

class LoginController extends Controller
{


    public function __construct()
    {


    }

    /**
     * @return string
     * @@ return login view
     * @@ access file login.blade.php from views.admin.login
     */


    public function login()
    {

        if (auth()->check() && auth()->user()->hasAnyRoles()) {
            return redirect(route('admin.home'));
            // return view('admin.auth.login');
        }
        return view('admin.auth.login');
    }


    public function postLogin(Request $request)
    {


        //return $request->all();
        $this->validate($request, [
            'provider' => 'required|email',
            'password' => 'required'
        ]);

        //$field = filter_var($request->provider, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $is_user = User::where('email', $request->provider)->first();
        if(!$is_user){
            session()->flash('error', 'مستخدم غير موجود');
            return redirect()->back();
        }

        if($is_user->is_suspend == 1){
            return redirect()->route('admin.login')->with('error', 'مستخدم معطل');
        }

        if (Auth::attempt(['email' => $request->provider, 'password' => $request->password ,'is_active'=>1])) {
            $user = auth()->user();
            
            $login = UserLogin::where('user_id',$user->id)->first();
            if($login){
                $login->logins_count = $login->logins_count + 1;
                $login->save();
            }else{
                $newLogin = new UserLogin();
                $newLogin->user_id = $user->id ;
                $newLogin->logins_count = 1 ;
                $newLogin->save();
            }
            return redirect()->route('admin.home');
        }

        session()->flash('error', 'اسم المستخدم او كلمة المرور غير صحيح');
        return redirect()->back()->withInput();


    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->guest(route('admin.login'));

    }

}
