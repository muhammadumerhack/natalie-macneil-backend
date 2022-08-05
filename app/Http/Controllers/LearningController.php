<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Learning;
use App\Models\CompletedLearning;


class LearningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Learning::all();
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
            'title'=>'required|string',
            'course_id'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],422);
        }
        $learning = Learning::create( [
            'title'=>$request->title?$request->title:null,
            'description'=>$request->description?$request->description:null,
            'video_link'=>$request->video_link?$request->video_link:null,
            'course_id'=>$request->course_id?$request->course_id:null,
        ]);

        if(isset($request->thumbnail ) && $request->hasFile('thumbnail')){
            $file = $request->file('thumbnail');
            $file_name = time()."_".$file->getClientOriginalName();
            $file->move(public_path('content'),$file_name);
            $thumbnail = env('APPLICATION_URL').'natalie-macneil-backend/public/content/'.$file_name;
            $learning->thumbnail = $thumbnail;    
            $learning->save();
        }


        return response()->json([
            'message'=>'Chapter Created',
            'data'=> $learning,
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
        $learning =  Learning::find($id);

        if($learning){
            return response()->json([
                'message'=>'Chapter Found',
                'data'=> $learning,
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
        $learning = Learning::find($id);

        //if user found
        if($learning){

            
            if(isset($request->thumbnail ) && $request->hasFile('thumbnail')){
                $file = $request->file('thumbnail');
                $file_name = time()."_".$file->getClientOriginalName();
                $file->move(public_path('content'),$file_name);
                $thumbnail = env('APPLICATION_URL').'natalie-macneil-backend/public/content/'.$file_name;
                $learning->thumbnail = $thumbnail;    
            }
            if(isset($request->title)){
                $learning->title = $request->title;
            }
            if(isset($request->description)){
                $learning->description = $request->description;
            }
            if(isset($request->thumbnail)){
                $learning->thumbnail = $request->thumbnail;
            }
            if(isset($request->video_link)){
                $learning->video_link = $request->video_link;
            }
            if(isset($request->course_id)){
                $learning->course_id = $request->course_id;
            }
            if(isset($request->status)){
                $learning->status = $request->status;
            }
            
            $learning->save();            
            return response()->json([
                'message'=>'Learning Updated',
                'data'=> $learning,
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
        $learning = Learning::find($id);
        if($learning){
            
            if(Learning::destroy($id)){
                return response()->json([
                    'message'=>'Chapter Deleted',
                ],200);
    
            }
        }else{
            return response()->json([
                'message'=>'Chapter Not Found',
            ],404);
        } 

    }

    public function markChapterCompleted(Request $request){

        $validator = Validator::make($request->all(),[
            'user_id'=>'required',
            'chapter_id'=>'required',
            'status'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],422);
        }

        $learning = CompletedLearning::where('user_id',$request->user_id)->where('chapter_id',$request->chapter_id)->first();

        if($learning){
            $learning->status =  $request->status?$request->status:0;
            $learning->save();
        }else{
            $learning = CompletedLearning::create( [
                'user_id'=>$request->user_id,
                'chapter_id'=>$request->chapter_id,
                'status'=>1,
            ]);
        }

        return response()->json([
            'message'=>'Mark as completed',
            'data'=> $learning,
        ],200);
    
    }


    public function getLearningWRTCourse(Request $request){

        $validator = Validator::make($request->all(),[
            'course_id'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],422);
        }
        $learnings = Learning::where('course_id',$request->course_id)->get();
        if($request->user_id){
            foreach ($learnings as $learning) {
                $CompletedLearning = CompletedLearning::where('user_id',$request->user_id)->where('chapter_id',$learning)->first();
                if($CompletedLearning){
                    $learning->is_completed = $CompletedLearning->status;
                }else{
                    $learning->is_completed = 0;
                }
            }
        }

        return response()->json([
            'message'=>'all learnings',
            'data'=> $learnings,
        ],200);

    }

}
