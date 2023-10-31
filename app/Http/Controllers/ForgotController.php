<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ForgotMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotController extends Controller
{
    public function forgotpassword(Request $request){
        $request->validate([
            'email'=>'required'
        ]);
        $email=$request->email;

        $user=User::where('email',$email)->first();

        if(!$user){
            return response([
                'message'=>'Email Invalid'
            ],404);
        }
        $token=rand(10,100000);

        DB::table('password_reset_tokens')->insert([
            'email'=>$email,
            'token'=>$token
        ]);

        Mail::to($email)->send(new ForgotMail($token));

        return response([
            'message'=>'Reset Password mail send on your email'
        ],200);

    }

    public function resetpassword(Request $request){

        $request->validate([
            'email'=>'required|email',
            'token'=>'required',
            'password'=>'required|min:6|confirmed'
        ]);
        $email=$request->email;
        $token=$request->token;
        $password=Hash::make($request->password);


        $emailcheck=DB::table('password_reset_tokens')->where('email',$email)->first();
        $pincheck=DB::table('password_reset_tokens')->where('token',$token)->first();

        if(!$emailcheck){
            return response([
                'message'=>'Email not Found'
            ],400);
        }
        if(!$pincheck){
            return response([
                'message'=>'PINCODE Invalid'
            ],400);
        }

        DB::table('users')->where('email',$email)->update(['password'=>$password]);
        DB::table('password_reset_tokens')->where('email',$email)->delete();

        return response([
            'message'=>'Password Changed Successfully'
        ],200);


    }
}
