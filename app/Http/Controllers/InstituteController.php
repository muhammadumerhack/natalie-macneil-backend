<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Institute;

class InstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Institute::all();
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
            'name'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],422);
        }


        $institute =  Institute::create( $request->all() );
        return response()->json([
            'message'=>'Institute Created',
            'data'=> $institute,
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
        $institute = Institute::find($id);

        if($institute){
            return response()->json([
                'message'=>'Institute Found',
                'data'=> $institute,
            ],200);

        }else{

            return response()->json([
                'message'=>'Institute Not Found',
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
        $institute = Institute::find($id);
        if($institute){

            $institute = $institute->update($request->all());
            $institute = Institute::find($id);
            return response()->json([
                'message'=>'Institute Updated',
                'data'=> $institute
            ],200);
    
        }else{

            return response()->json([
                'message'=>'Institute Not Found',
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
        $institute = Institute::find($id);
        if($institute){
            if(Institute::destroy($id)){
                return response()->json([
                    'message'=>'Institute Deleted',
                ],200);

            }
        }else{
            return response()->json([
                'message'=>'Group Not Found',
            ],404);

        }

    }
}
