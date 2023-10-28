<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){

    try {
           if(Auth::attempt($request->only('email','password'))){
            $user=Auth::User();
            $token=$user->createToken('auth_token')->accessToken;
            return response([
                'message'=>'Successfully Login',
                'token'=>$token,
                'user'=>$user
            ],200);
        }
    } catch (Exception $th) {
        return response([
            'message'=>$th->getMessage()
        ],200);
    }

        return response([
            'message'=>'Invalid Email or Password'
        ],200);
    }

    public function register(Request $request){

        try {

            $request->validate([
            'name'=>'required|max:255',
            'email'=>'required|unique:users|max:255',
            'password'=>'required|min:6|confirmed',

        ]);
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ],200);

        $token=$user->createToken('auth_token')->accessToken;

        return response([
            'message'=>'Successfully Registered',
            'token'=>$token,
            'user'=>$user
        ],200);

        }  catch (Exception $th) {
            return response([
            'message'=>$th->getMessage()
        ],200);
    }


    }
}
