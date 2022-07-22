<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Otp;
use Mail;
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
        $otpInitiate = new Otp;
        $otpObj = $otpInitiate->generate($request->email, 6, 5);
        $otpObj->status == true ? $otp = $otpObj->token:$otp =null;
        if($otp != null){

            $object_arr = array('to'=>$request->email,'name'=>$user->name,'code'=>$otp,'userName'=>'','password'=>'');
            Mail::send('mail', $object_arr, function($message) use ($object_arr){
                $message->to($object_arr['to'], $object_arr['name'])->subject('Verify Otp');
                $message->from('no-reply@nasenicertveri.com','Naseni Certveri'); 
             });

            // $this->sendMail('Verify Otp',array('to'=>$request->email,'name'=>$user->name,'code'=>$otp));
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        return response()->json([
            'message'=>'Check Your Email',
            'email'=>$user->email
        ],200);

    }
    public function verifyOtp(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'otp' => 'required|string',
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
        };
        $otpInitiate = new Otp;
        $otpObj = $otpInitiate->validate($request->email, $request->otp);
        $token = $user->createToken('myapptoken')->plainTextToken;
        if($otpObj->status == true){
            return response()->json([
                'status'=>true,
                'message'=>'User Logged in',
                'user'=>$user,
                'token'=>$token
            ],200);
        }else{
            return response()->json($otpObj,201);
        };
        
        if($otp != null){

            $object_arr = array('to'=>$request->email,'name'=>$user->name,'code'=>$otp,'userName'=>'','password'=>'');
            Mail::send('mail', $object_arr, function($message) use ($object_arr){
                $message->to($object_arr['to'], $object_arr['name'])->subject('Verify Otp');
                $message->from('no-reply@nasenicertveri.com','Naseni Certveri'); 
             });

            // $this->sendMail('Verify Otp',array('to'=>$request->email,'name'=>$user->name,'code'=>$otp));
        }
    

    }
    /**
     * Send Mail
     */
    public function sendMail($subject,$object) {
        Mail::send('mail', $object, function($message) {
           $message->to($object->to, $object->name)->subject($subject);
           $message->from('no-reply@nasenicertveri.com','Naseni Certveri'); 
        });
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
