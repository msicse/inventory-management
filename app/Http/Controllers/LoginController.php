<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function postLogin(Request $req){

        $this->validate($req,array(
           'email' => 'required|max:255|email',
           'password' => 'required|min:6',
       ));

       if(Auth::attempt(['email' => $req->email, 'password' => $req->password])){

            UserLogHelper::log('login', 'Successfully Login');

            return redirect()->intended('dashboard');
       }else{
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);

       }
   }

   public function logout(Request $request)
    {

        UserLogHelper::log('logout', 'Successfully Logout');
        Auth::logout();
        return redirect('/');
    }
}
