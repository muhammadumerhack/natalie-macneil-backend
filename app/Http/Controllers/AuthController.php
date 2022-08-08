<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Setting;
class AuthController extends Controller
{
    /**
     * login function
     */
    public function login(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password'=>'required|string' 
        ]);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],400);
        }
        
        $user = User::where('email',$request->email)->first();
        
        // if user not found or cred not matched
        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                'message'=> 'Credentials Not Matched'
            ],401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $setting = Setting::find(1);
        $user->is_form_enabled = "no";
        if($setting){
            $user->is_form_enabled = $setting->value;
        }
        return response()->json([
            'status'=>true,
            'message'=>'User Logged in',
            'user'=>$user,
            'token'=>$token
        ],200);

    }

    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed',
            'invite_code'=>'required|string',
            'role_id'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],422);
        }
        $password =$request->password;
        $full_name = $request->first_name." ".$request->last_name;
        $user = User::create( [
            'name'=>$full_name,
            'username'=>$request->email,
            'email'=>$request->email,
            'password'=>  bcrypt($password),
            'role_id'=>  $request->role_id,
            'status'=>  'active',
        ] );
        return response()->json([
            'message'=>'User Created',
            'data'=> $user,
        ],201);

    }
    /**
     * logout function
    */
    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return response()->json([
            'message'=>'Logged Out',
        ],200);

    }

    /**
     * VERIFY Token
     */
    public function verifyToken(Request $request){
        // $token = request()->user()->currentAccessToken()->token;
        $user = auth()->user();
        $token = $request->bearerToken();

        return response()->json([
            'message'=>'Verified',
            'user' => $user,
            'token'=> $token
        ],200);

    }

}
