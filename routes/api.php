<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstituteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\SettingsController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * PUBLIC ROUTES
 */

//login route
Route::post('login', [AuthController::class, 'login']); 
Route::post('register', [AuthController::class, 'register']); 
Route::post('serviceOptionsTest', [UserController::class,'serviceOptions'] ); 

/**
 * PROTECTED ROUTES
 */
Route::group(['middleware'=>['auth:sanctum']],function(){

    // logout routes
    Route::post('logout', [AuthController::class, 'logout']); 
    // verify token 
    Route::post('verify_token', [AuthController::class, 'verifyToken']); 


    // Users Routs
    Route::resource('users', UserController::class ); 
    Route::post('serviceOptions', [UserController::class,'serviceOptions'] ); 
    Route::get('users_admin', [UserController::class,'getAllAdminUsers'] ); 
    Route::get('users_clients', [UserController::class,'getAllClients'] ); 

    // Learning Routs
    Route::resource('learnings', LearningController::class ); 
    Route::post('markCompleted', [LearningController::class,'markChapterCompleted'] ); 
    Route::post('updateLearningData/{id}', [LearningController::class,'updateLearningData'] ); 
    Route::get('getCourseLearnings', [LearningController::class,'getLearningWRTCourse'] ); 
    

    //Settings Routes
    Route::post('saveSettings', [SettingsController::class,'saveSettings'] ); 
    Route::get('get_settings/{key}', [SettingsController::class,'getSettingsByKey'] ); 

});

