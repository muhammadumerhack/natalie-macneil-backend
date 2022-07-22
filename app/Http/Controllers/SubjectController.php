<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Subject;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function getSubjectsByCode($code){

        $subjects = Subject::where('certificate_code',$code)->first();
        if($subjects){

            return response()->json([
                'message'=>'data Found',
                'data'=> $subjects,
            ],200);

        }else{

            return response()->json([
                'message'=>'Subjects Not Found',
            ],404);

        }

    }
}
