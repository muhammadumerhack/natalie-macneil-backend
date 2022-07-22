<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Group;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Group::all();
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
            'amount'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],422);
        }


        $group =  Group::create( $request->all() );
        return response()->json([
            'message'=>'Group Created',
            'data'=> $group,
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

        $group =  Group::find($id);

        if($group){
            return response()->json([
                'message'=>'Group Found',
                'data'=> $group,
            ],200);
                
        }else{
            return response()->json([
                'message'=>'Group Not Found',
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
        $group = Group::find($id);
        
        if($group){
            $group = $group->update($request->all());
            $group = Group::find($id);
            return response()->json([
                'message'=>'Group Updated',
                'data'=> $group
            ],200);

        }else{

            return response()->json([
                'message'=>'Group Not Found',
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
        $group = Group::find($id);
        if($group){
            if(Group::destroy($id)){
                return response()->json([
                    'message'=>'Group Deleted',
                ],200);
            }
        }else{

            return response()->json([
                'message'=>'Group Not Found',
            ],404);

        }
    }
}
