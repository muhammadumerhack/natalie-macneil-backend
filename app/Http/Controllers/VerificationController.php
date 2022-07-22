<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Verification;
use App\Models\Group;
use App\Models\User;
use App\Models\Certificate;

class VerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data = array();
        if(isset($request->user_id) && $request->user_id!=""){

           $data =  Verification::join('users','users.id','verifications.verified_by')
            ->join('certificates','certificates.id','verifications.certificate_id')
            ->select(
                'users.name as verifier',
                'certificates.code as code',
                'verifications.verified_by',
                'verifications.certificate_id',
                'verifications.created_at',
                'verifications.amount',
                'verifications.institute_id',
                'verifications.id as id'
            )->where('users.id',$request->user_id)->orderBy('id','desc')->get();
    
        }else{
            $data = Verification::join('users','users.id','verifications.verified_by')
            ->join('certificates','certificates.id','verifications.certificate_id')
            ->select(
                'users.name as verifier',
                'certificates.code as code',
                'verifications.verified_by',
                'verifications.certificate_id',
                'verifications.created_at',
                'verifications.amount',
                'verifications.id as id'
            )->orderBy('id','desc')->get();
        }
        return $data;
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
            'certificate_id' => 'required',
            'verified_by' => 'required',
        ]);        
        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],422);
        }

        $user = User::find($request->verified_by); 

        $verified_by = $request->verified_by;
        $user_id = $request->verified_by;
        //if staff verifying certificate
        if($user->role_id == 3){
            $verified_by = $user->parent_verifier_id;
        }


            $user = User::find($verified_by); 
            if($user->fixed_fees!=null && $user->fixed_fees > 0){
                $group_id = null;
                $amount = $user->fixed_fees?$user->fixed_fees:0;
            }else{
                $group_id = $user->group_id; 
                $group = Group::find($group_id);
                $amount = $group->amount?$group->amount:0;    
            }

            
            $certificate = Certificate::find($request->certificate_id);

            $verification =  Verification::create([
                'certificate_id' => $request->certificate_id,
                'verified_by' => $verified_by,
                'institute_id' => $certificate->institute_id,
                'group_id' => $group_id,
                'country' => $user->country,
                'amount'=> $amount,
                'user_id'=>$user_id
            ] );
            return response()->json([
                'message'=>'Verification Created',
                'data'=> $verification,
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

        $verification =  Verification::find($id);

        if($verification){
            return response()->json([
                'message'=>'Verification Found',
                'data'=> $verification,
            ],200);
                
        }else{
            return response()->json([
                'message'=>'Verification Not Found',
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
        $verification = Verification::find($id);
        
        if($verification){
            $verification = $verification->update($request->all());
            $verification = Verification::find($id);
            return response()->json([
                'message'=>'Verification Updated',
                'data'=> $verification
            ],200);

        }else{

            return response()->json([
                'message'=>'Verification Not Found',
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
        $verification = Verification::find($id);
        if($verification){
            if(Verification::destroy($id)){
                return response()->json([
                    'message'=>'Verification Deleted',
                ],200);
            }
        }else{

            return response()->json([
                'message'=>'Verification Not Found',
            ],404);

        }
    }

    /**
     * Display resources by user_id/verified_by.
     *
     * @param  string  $user_id
     * @return \Illuminate\Http\Response
     */
    public function getVerificationsByuserId($user_id)
    {
        $verifications = Verification::where('verified_by',$user_id)->get();
        if(!empty($verifications)){

            return response()->json([
                'message'=>'data Found',
                'data'=> $verifications,
            ],200);

        }else{

            return response()->json([
                'message'=>'Verifications Not Found',
            ],404);

        }
    }

    public function getVerificationsByInvoiceId($invoice_id)
    {
        
        $verifications = Verification::where('invoice_id',$invoice_id)->get();
        if(!empty($verifications)){
            foreach ($verifications as $key => $verification) {
                $certificate = Certificate::find($verification->certificate_id);
                $verifications[$key]->code = $certificate->code;
            }

            return response()->json([
                'message'=>'data Found',
                'data'=> $verifications,
            ],200);

        }else{

            return response()->json([
                'message'=>'Verifications Not Found',
            ],404);

        }
    }

}
