<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Admin;
use Illuminate\Support\Str;

class AdminLoginController extends Controller
{
	public function __contruct()
	{
		//$this->middleware('guest:admin');
		$this->middleware('guest:admin',['except'=>['logout']]);
    }
    public function showLoginForm()
    {
    	return view('auth.admin_login');
    }

    public function login(Request $request)
    {
    	//validate the form data
    	$this->validate($request,[
    		'email'=>'required|email',
    		'password'=>'required|min:6'
    	]);

    	//Attempt to log the user in
    	if(Auth::guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password],$request->remember))
    	{
            $admin=Admin::where('email',$request->email)->first();
            
    		//if successful.then redirect to their intended location
           
    		return redirect()->intended(route('admin.dashboard'));
    	}
    	//if unsuccessful , then redirect back to the login with the form data
    	return redirect()->back()->withInput($request->only('email','remember'));
    }

    // public function logout()
    // {
    //     Auth::guard('admin')->logout();
    //     //return redirect('/');
    //     return redirect('admin/login');
    // }
}
