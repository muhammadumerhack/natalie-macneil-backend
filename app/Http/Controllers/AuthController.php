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
        $invite_code = "";
        $setting = Setting::where('key', 'invite_code')->first();
        if($setting){
            $invite_code = $setting->value;
        }

        if($invite_code != $request->invite_code){
            return response()->json([
                'message'=>'invalid'
            ],200);
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

        $this->kartraAddLeadInList($request->email,$request->first_name,$request->last_name);

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


    public function kartraAddLeadInList($email,$first_name,$last_name){


        $ch = curl_init();
        // CONNECT TO THE API SYSTEM VIA THE APP ID, AND VERIFY MY API KEY AND PASSWORD, IDENTIFY THE LEAD, AND SEND THE ACTIONS…
        curl_setopt($ch, CURLOPT_URL,"https://app.kartra.com/api");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
        http_build_query(
                array(
                    'app_id' => 'QYVUtgNszLmK',
                    'api_key' => 'wyazMKZW',
                    'api_password' => 'crzQeEqAOHtl',
                    'lead' => array(
                        'email' => $email,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                    ),
                    'actions' => array(
                        '0' =>array(
                            'cmd' => 'create_lead',
                            'list_name' => 'Nat MacNeil'
                        ),
                        '1' =>array(
                            'cmd' => 'subscribe_lead_to_list',
                            'list_name' => 'Nat MacNeil'
                        ),
                    )
                )
            )
        );
        // REQUEST CONFIRMATION MESSAGE FROM API…
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $server_json = json_decode($server_output);
        
        // **Status**
        //"Error"
        //"Success"
        // CONDITIONAL FOR FURTHER INSTRUCTIONS…
        return $server_json->status;
    
    }

}
