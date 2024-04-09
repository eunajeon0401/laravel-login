<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthManager extends Controller
{
    function login(){
        return view('login');
    }

    function register(){
        return view('register');
    }

    function loginPost(Request $req){
        $req->validate([
            'email'=> 'required',
            'password' => 'required',
        ]);

        $check =$req->only('email', 'password');
        if(Auth::attempt($check)){
            return redirect()->intended(route('home'));
        }
        return redirect(route('login'))->with("error", "Login details are not valid");
    }

    function registerPost(Request $req){

        $req->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);

        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password);
        $user = User::create($data);

        if(!$user){
            return redirect('register')->with("error", "Registration failed, try again");
        }
        return redirect(route('login'))->with("success", "Registration success");
    }

    function logout(){
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}
