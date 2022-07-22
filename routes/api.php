<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstituteController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MailController;


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
Route::post('login/verify/otp', [AuthController::class, 'verifyOtp']); 
Route::get('sendbasicemail',[MailController::class, 'basic_email']);
Route::get('sendhtmlemail',[MailController::class, 'html_email']);
Route::get('sendattachmentemail',[MailController::class, 'attachment_email']);


/**
 * PROTECTED ROUTES
 */
Route::group(['middleware'=>['auth:sanctum']],function(){

    // logout routes
    Route::post('logout', [AuthController::class, 'logout']); 
    // verify token 
    Route::post('verify_token', [AuthController::class, 'verifyToken']); 


    // institutes Routes
    Route::resource('institutes', InstituteController::class ); 
    // Groups Routes
    Route::resource('groups', GroupController::class ); 
    // Users Routs
    Route::resource('users', UserController::class ); 
    Route::get('users_admin', [UserController::class,'getAllAdminUsers'] ); 
    Route::get('users_verifier', [UserController::class,'getAllVerifiers'] ); 
    Route::get('users_staff', [UserController::class,'getStaff'] ); 


    // Verifications Routs
    Route::resource('verifications', VerificationController::class );
    Route::get('verification_by_user/{user_id}', [VerificationController::class,'getVerificationsByuserId'] );
    Route::get('verification_by_invoice/{invoice_id}', [VerificationController::class,'getVerificationsByInvoiceId'] );
    

    // Certificate routes
    Route::resource('certificates', CertificateController::class );
    Route::get('certificate_by_code/{code}', [CertificateController::class,'getCertificationByCode'] );
    Route::get('certificate_by_national_id/{national_id}', [CertificateController::class,'getCertificationByNationalId'] );
    


    // subject routes
    Route::get('subjects/{code}', [SubjectController::class,'getSubjectsByCode'] );

    // Reports routes
    Route::get('verification_report', [ReportsController::class,'verificationReport'] );
    Route::get('dashboard_stats', [ReportsController::class,'dataStats'] );

    //Invoices Routes
    Route::get('genrateinvoice',[InvoiceController::class,'createInvoices']);
    Route::get('getAllInvoices',[InvoiceController::class,'index']);
    Route::get('updateInvoiceStatus',[InvoiceController::class,'updateInvoiceStatus']);

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();

// });
