<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;

class LoginController extends Controller
{
    public function postLogin(Request $req){

       $this->validate($req,array(
           'email' => 'required|max:255',
           'password' => 'required|min:6',
       ));
       //With custom message
       // $rules = [
       //     'email' => 'required|unique:posts|max:255',
       //     'password' => 'required|min:6',
       // ];
       // $custom_messag = [
       //     'email.required' => 'Email Address is required',
       //     'password.required' => 'Password is required',
       // ];

       //$this->validate($req, $rules, $custom_messag);

       if(Auth::attempt(['email' => $req->email, 'password' => $req->password])){
           return redirect()->route('admin.dashboard');
       }else{
           Session()->flash('error_message', 'Invalid Email or Password');
           return redirect()->back();
       }
   }
}
