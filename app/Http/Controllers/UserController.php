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
            'status'=>'required|string',

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
            'country'=>$request->country?$request->country:"",
            'phone'=>$request->phone?$request->phone:"",
            'account_id'=>$request->account_id?$request->account_id:null,
            'group_id'=>$request->group_id?$request->group_id:null,
            'role_id'=>$request->role_id?$request->role_id:null,
            'fixed_fees'=>$request->fixed_fees?$request->fixed_fees:null,
            'status'=>$request->status?$request->status:null,
            'allow_users'=>$request->allow_users?$request->allow_users:0,
            'allow_verifiers'=>$request->allow_verifiers?$request->allow_verifiers:0,
            'allow_institutes'=>$request->allow_institutes?$request->allow_institutes:0,
            'allow_groups'=>$request->allow_groups?$request->allow_groups:0,
            'allow_reports'=>$request->allow_reports?$request->allow_reports:0,
            'allow_settings'=>$request->allow_settings?$request->allow_settings:0,
            'allow_invoices'=>$request->allow_invoices?$request->allow_invoices:0,
            'add_users' => $request->add_users?$request->add_users:0,
            'edit_users' => $request->edit_users?$request->edit_users:0,
            'del_users' => $request->del_users?$request->del_users:0,
            'add_verifiers' => $request->add_verifiers?$request->add_verifiers:0,
            'edit_verifiers' => $request->edit_verifiers?$request->edit_verifiers:0,
            'del_verifiers' => $request->del_verifiers?$request->del_verifiers:0,
            'add_institutes' => $request->add_institutes?$request->add_institutes:0,
            'edit_institutes' => $request->edit_institutes?$request->edit_institutes:0,
            'del_institutes' => $request->del_institutes?$request->del_institutes:0,
            'add_groups' => $request->add_groups?$request->add_groups:0,
            'edit_groups' => $request->edit_groups?$request->edit_groups:0,
            'del_groups' => $request->del_groups?$request->del_groups:0,
            'allow_new_verification' => $request->allow_new_verification?$request->allow_new_verification:0,
            'allow_verifications' => $request->allow_verifications?$request->allow_verifications:0,
            'parent_verifier_id' => $request->parent_verifier_id?$request->parent_verifier_id:0,
        ] );
        $user->allow_invoices = $request->allow_invoices?$request->allow_invoices:0;
        $user->save();
        $object_arr = array('to'=>$user->email,'name'=>$user->name,'code'=>'','url'=>env('MAIL_SIGNIN_URL'),'userName'=>$user->email,'password'=>$password);
            Mail::send('mail', $object_arr, function($message) use ($object_arr){
                $message->to($object_arr['to'], $object_arr['name'])->subject('Welcome to Naseni Certveri');
                $message->from('no-reply@nasenicertveri.com','Naseni Certveri'); 
             });
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
                    'country'=>$request->country?$request->country:"",
                    'email'=>$request->email?$request->email:$user->email,
                    'phone'=>$request->phone?$request->phone:"",
                    'account_id'=>$request->account_id?$request->account_id:null,
                    'group_id'=>$request->group_id?$request->group_id:null,
                    'role_id'=>$request->role_id?$request->role_id:null,
                    'fixed_fees'=>$request->fixed_fees?$request->fixed_fees:null,
                    'status'=>$request->status?$request->status:null,
                    'allow_users'=>$request->allow_users?$request->allow_users:0,
                    'allow_verifiers'=>$request->allow_verifiers?$request->allow_verifiers:0,
                    'allow_institutes'=>$request->allow_institutes?$request->allow_institutes:0,
                    'allow_groups'=>$request->allow_groups?$request->allow_groups:0,
                    'allow_reports'=>$request->allow_reports?$request->allow_reports:0,
                    'allow_settings'=>$request->allow_settings?$request->allow_settings:0,
                    'allow_invoices'=>$request->allow_invoices?$request->allow_invoices:0,
                    'add_users' => $request->add_users?$request->add_users:0,
                    'edit_users' => $request->edit_users?$request->edit_users:0,
                    'del_users' => $request->del_users?$request->del_users:0,
                    'add_verifiers' => $request->add_verifiers?$request->add_verifiers:0,
                    'edit_verifiers' => $request->edit_verifiers?$request->edit_verifiers:0,
                    'del_verifiers' => $request->del_verifiers?$request->del_verifiers:0,
                    'add_institutes' => $request->add_institutes?$request->add_institutes:0,
                    'edit_institutes' => $request->edit_institutes?$request->edit_institutes:0,
                    'del_institutes' => $request->del_institutes?$request->del_institutes:0,
                    'add_groups' => $request->add_groups?$request->add_groups:0,
                    'edit_groups' => $request->edit_groups?$request->edit_groups:0,
                    'del_groups' => $request->del_groups?$request->del_groups:0,
                    'allow_new_verification' => $request->allow_new_verification?$request->allow_new_verification:0,
                    'allow_verifications' => $request->allow_verifications?$request->allow_verifications:0,
                    'parent_verifier_id' => $request->parent_verifier_id?$request->parent_verifier_id:0,
                        
                ]);
        
            }else{
                $user = $user->update([
                    'name'=>$request->name?$request->name:$user->name,
                    'country'=>$request->country?$request->country:"",
                    'phone'=>$request->phone?$request->phone:"",
                    'account_id'=>$request->account_id?$request->account_id:null,
                    'email'=>$request->email?$request->email:$user->email,
                    'group_id'=>$request->group_id?$request->group_id:null,
                    'role_id'=>$request->role_id?$request->role_id:null,
                    'fixed_fees'=>$request->fixed_fees?$request->fixed_fees:null,
                    'status'=>$request->status?$request->status:null,
                    'allow_users'=>$request->allow_users?$request->allow_users:0,
                    'allow_verifiers'=>$request->allow_verifiers?$request->allow_verifiers:0,
                    'allow_institutes'=>$request->allow_institutes?$request->allow_institutes:0,
                    'allow_groups'=>$request->allow_groups?$request->allow_groups:0,
                    'allow_reports'=>$request->allow_reports?$request->allow_reports:0,
                    'allow_settings'=>$request->allow_settings?$request->allow_settings:0, 
                    'allow_invoices'=>$request->allow_invoices?$request->allow_invoices:0,
                    'add_users' => $request->add_users?$request->add_users:0,
                    'edit_users' => $request->edit_users?$request->edit_users:0,
                    'del_users' => $request->del_users?$request->del_users:0,
                    'add_verifiers' => $request->add_verifiers?$request->add_verifiers:0,
                    'edit_verifiers' => $request->edit_verifiers?$request->edit_verifiers:0,
                    'del_verifiers' => $request->del_verifiers?$request->del_verifiers:0,
                    'add_institutes' => $request->add_institutes?$request->add_institutes:0,
                    'edit_institutes' => $request->edit_institutes?$request->edit_institutes:0,
                    'del_institutes' => $request->del_institutes?$request->del_institutes:0,
                    'add_groups' => $request->add_groups?$request->add_groups:0,
                    'edit_groups' => $request->edit_groups?$request->edit_groups:0,
                    'del_groups' => $request->del_groups?$request->del_groups:0,
                    'allow_new_verification' => $request->allow_new_verification?$request->allow_new_verification:0,
                    'allow_verifications' => $request->allow_verifications?$request->allow_verifications:0,
                    'parent_verifier_id' => $request->parent_verifier_id?$request->parent_verifier_id:0,
                ]);
            }
            
            $user = User::find($id);
            $user->allow_invoices = $request->allow_invoices?$request->allow_invoices:0;
            $user->save();
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
     * get all Verifiers users from storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAllVerifiers(){
        return User::leftJoin('groups','groups.id','users.group_id')
        ->select(
            'users.id',
            'users.role_id',
            'users.group_id',
            'users.name',
            'users.username',
            'users.email',
            'users.created_at',
            'users.country',
            'users.fixed_fees',
            'groups.name as group_name',
            'users.status',
            'users.phone',
            'users.account_id'
        )
        ->where('role_id',2)->orderBy('id','desc')->get();
    }


    public function getStaff(Request $request){

        $uers =  User::where('role_id',3);    
        if(isset($request->parent_verifier_id) && $request->parent_verifier_id>0){
            $uers->where('parent_verifier_id',$request->parent_verifier_id);
        }
        $uers =  $uers->orderBy('id','desc')->get();    
    
        return $uers; 
    }


}
