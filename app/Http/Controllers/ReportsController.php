<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Verification;
use App\Models\Group;
use App\Models\User;
use App\Models\Certificate;
use App\Models\Institute;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verificationReport(Request $request)
    {
        $data = array();
        $verifications = Verification::where('id','>',0);

        if(isset($request->group_id)){
           $verifications = $verifications->where('group_id',$request->group_id);
        }

        if(isset($request->institute_id)){
            $verifications = $verifications->where('institute_id',$request->institute_id);
         }
         
         if(isset($request->country)){
            $verifications = $verifications->where('country',$request->country);
         }

         if(isset($request->verified_by)){
            $verifications = $verifications->where('verified_by',$request->verified_by);
         }

        $verifications = $verifications->get();
        // return $verifications
        foreach ($verifications as $key => $verification) {
            $user = User::find($verification->verified_by);
            $verifier = "Not found";
            if($user){
                $verifier = $user->name;
            }
            $certificate = Certificate::find($verification->certificate_id);
            $institute = Institute::find($verification->institute_id);            
            $group = Group::find($verification->group_id);            
            $group_name = "Fixed Fess";
            if($group){
                $group_name = $group->name;
            }

            $data[]=array(
                'verifier'=>$verifier,
                'institute'=>$institute->name,
                'institute_id'=>$institute->id,
                'certificate_number'=>$certificate->code,
                'amoun'=>$verification->amount,
                'country'=>$verification->country,
                'group'=>$group_name,
            );
        }

        return $data;
    }

    public function dataStats(){
        $data = array(
            'users' => count(User::where('role_id',1)->get()),
            'verifiers'=>count(User::where('role_id',2)->get()),
            'groups'=>count(Group::all()),
            'verifications'=>count(Verification::all()),
            'institutes'=>count(Institute::all())
        );        
        return $data;
    }
}
