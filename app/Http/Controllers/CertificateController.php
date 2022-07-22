<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Certificate;
use App\Models\Verification;
class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Certificate::all();
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
            'code'=>'required|unique:verifications,code',
            'school'=>'required',
            'candidate'=>'required',
            'year'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],422);
        }


        $verification =  Certificate::create( $request->all() );
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

        $verification =  Certificate::find($id);

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
        $verification = Certificate::find($id);
        
        if($verification){
            $verification = $verification->update($request->all());
            $verification = Certificate::find($id);
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
        $verification = Certificate::find($id);
        if($verification){
            if(Certificate::destroy($id)){
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
     * Display the specified resource by code.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function getCertificationByCode($code)
    {
        $certificate = Certificate::where('code',$code)->first();

        $certificate->is_verified = false;
        // check if it already verified
        $is_verified = Verification::where('certificate_id',$certificate->id)->first();
        if($is_verified){
            $certificate->is_verified = true;
        }

        if($certificate){

            return response()->json([
                'message'=>'Certificate Found',
                'data'=> $certificate,
            ],200);

        }else{

            return response()->json([
                'message'=>'Certificate Not Found',
            ],404);

        }
    }

    public function getCertificationByNationalId($national_id)
    {
        $certificate = Certificate::where('national_id',$national_id)->first();

        $certificate->is_verified = false;
        // check if it already verified
        $is_verified = Verification::where('certificate_id',$certificate->id)->first();
        if($is_verified){
            $certificate->is_verified = true;
        }

        if($certificate){

            return response()->json([
                'message'=>'Certificate Found',
                'data'=> $certificate,
            ],200);

        }else{

            return response()->json([
                'message'=>'Certificate Not Found',
            ],404);

        }
    }


}

