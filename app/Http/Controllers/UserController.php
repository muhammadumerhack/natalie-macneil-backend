<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Mail;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|string',
            'username'=>'required|string|unique:users,username',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed',

        ]);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],422);
        }
        $password =$request->password;  
        $user = User::create( [
            'name'=>$request->name,
            'username'=>$request->username,
            'email'=>$request->email,
            'password'=>  bcrypt($password),
            'status'=>$request->status?$request->status:null,
        ] );
        return response()->json([
            'message'=>'User Created',
            'data'=> $user,
        ],201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user =  User::find($id);

        if($user){
            return response()->json([
                'message'=>'User Found',
                'data'=> $user,
            ],200);
                
        }else{
            return response()->json([
                'message'=>'User Not Found',
            ],404);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        //if user found
        if($user){

            //if changing password
            if(isset($request->password) && $request->password != ""){

                $validator = Validator::make($request->all(),[
                    'password'=>'string|confirmed',        
                ]);
        
                if($validator->fails()){
                    return response()->json([
                        $validator->errors()
                    ],422);
                }
        
                $user = $user->update([
                    'name'=>$request->name?$request->name:$user->name,
                    'password'=> bcrypt($request->password),
                    'email'=>$request->email?$request->email:$user->email,
                    'role_id'=>$request->role_id?$request->role_id:null,
                    'status'=>$request->status?$request->status:null,                        
                ]);
        
            }else{
                $user = $user->update([
                    'name'=>$request->name?$request->name:$user->name,
                    'email'=>$request->email?$request->email:$user->email,
                    'role_id'=>$request->role_id?$request->role_id:null,
                    'status'=>$request->status?$request->status:null,
                ]);
            }
            return response()->json([
                'message'=>'User Updated',
                'data'=> $user,
            ],200);
                
        }else{
            return response()->json([
                'message'=>'User Not Found',
            ],404);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user){
            
            if(User::destroy($id)){
                return response()->json([
                    'message'=>'User Deleted',
                ],200);
    
            }
        }else{
            return response()->json([
                'message'=>'User Not Found',
            ],404);
        } 
    }

    /**
     * get all admin users from storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAllAdminUsers(){
        return User::where('role_id',1)->orderBy('id','desc')->get();
    }

    /**
     * get all Clients users from storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAllClients(){
        return User::where('role_id',2)->orderBy('id','desc')->get();    
    }

    /**
     * Update the specified resource's service type in storage.
     */
    public function serviceOptions(Request $request)
    {
        $user = User::find($request->user_id);

        //if user found
        if($user){
            $page_content = null;
            if($request->hasFile('page_content')){
                $file = $request->file('page_content');
                $file_name = time()."_".$file->getClientOriginalName();
                $file->move(public_path('content'),$file_name);
                $page_content = env('APPLICATION_URL').'natalie-macneil-backend/public/content/'.$file_name;
            }
            $privacy_content = null;
            if($request->hasFile('privacy_content')){
                $file = $request->file('privacy_content');
                $file_name = time()."_".$file->getClientOriginalName();
                $file->move(public_path('content'),$file_name);
                $privacy_content = env('APPLICATION_URL').'natalie-macneil-backend/public/content/'.$file_name;
            }
            $terms_content = null;
            if($request->hasFile('terms_content')){
                $file = $request->file('terms_content');
                $file_name = time()."_".$file->getClientOriginalName();
                $file->move(public_path('content'),$file_name);
                $terms_content = env('APPLICATION_URL').'natalie-macneil-backend/public/content/'.$file_name;
            }

            $user = $user->update([
                'service_type'=>$request->service_type?$request->service_type:"pending",
                'page_type'=>$request->page_type?$request->page_type:null,
                'branding'=>$request->branding?$request->branding:null,
                'funnel_platform'=>$request->funnel_platform?$request->funnel_platform:null,
                'funnel_emails'=>$request->funnel_emails?$request->funnel_emails:null,
                'page_content'=>$page_content,
                'privacy_content'=>$privacy_content,
                'terms_content'=>$terms_content,
                'service_status'=>$request->service_status?$request->service_status:null,
            ]);
            return response()->json([
                'message'=>'User Updated',
                'data'=> $user,
            ],200);
                
        }else{
            return response()->json([
                'message'=>'User Not Found',
            ],404);
        }


    }


}
