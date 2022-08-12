<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;

class SettingsController extends Controller
{

    public function saveSettings(Request $request){
        $validator = Validator::make($request->all(),[
            'key'=>'required',
            'value'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],422);
        }

        $setting = Setting::where('key', $request->key)->first();
        if($setting){
            $setting->value = $request->value;
            $setting->save();
        }else{
            $setting = Setting::create([
                'key'=>$request->key,
                'value'=>$request->value,    
            ]);
        }

        return response()->json([
            'message'=>'Setting Saved',
            'data'=> $setting,
        ],200);


       
    }


    public function inviteCode(){
        $setting = Setting::where('key', 'invite_code')->first();
        if($setting){
            return response()->json([
                'message'=>'invite code',
                'data'=> $setting->value,
            ],200);
    
        }else{
     
            return response()->json([
                'message'=>'invite code',
                'data'=> "",
            ],200);
            
        }
    }
}
