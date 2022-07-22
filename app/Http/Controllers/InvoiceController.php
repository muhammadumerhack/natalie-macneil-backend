<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Verification;
use App\Models\Invoice;
use DB;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $invoices = Invoice::join('users','invoices.verified_by','users.id')->select(
            'invoices.verified_by AS verified_by',
            'invoices.id AS invoice_id',
            'invoices.amount AS amount',
            'invoices.status AS status',
            'invoices.created_at AS created_at',
            'users.name AS verifier_name',
            'users.country AS country',
            'users.email AS email',
            'users.phone AS phone',
         );

         if(isset($request->start_date) && isset($request->end_date)){

            $start_date = Carbon::parse($request->start_date)->toDateString();
            $end_date = Carbon::parse($request->end_date)->toDateString();
            $invoices = $invoices->whereBetween('invoices.created_at',[$start_date.' 0000:00:00',$end_date.' 23:59:59']);
         }


         if(isset($request->verified_by)){
            $invoices = $invoices->where('verified_by',$request->verified_by);
         }

         if(isset($request->status)){
            $invoices = $invoices->where('invoices.status',$request->status);
         }

         $invoices = $invoices->orderby('invoice_id','DESC')->get();

        return response()->json([
            'message'=>'data Found',
            'data'=> $invoices,
            'status'=>true
        ],200);

        return response()->json([
            'message'=>'Not Found',
            'status'=>false
        ],200);

        }

    public function createInvoices(Request $request){
        $users = User::where(['role_id'=>2, 'status'=>'active'])->get();

        foreach ($users as $key => $user) {
            
            $verifications = Verification::where( ['verified_by'=>$user->id, 'invoiced'=>0] )->get();
            
            if(sizeof($verifications)>0){
                $invoice_total = 0;
                $invoice = Invoice::create([
                    'verified_by'=>$user->id,
                    'amount'=>$invoice_total,
                    'status'=>'unpaid'
                ]);
                foreach ($verifications as $key0 => $verification) {
                    $invoice_total+=$verification->amount;
                    $verification->invoiced = 1;
                    $verification->invoice_id = $invoice->id;
                    $verification->save();
                }//foreach verification

                $invoice->amount = $invoice_total;
                $invoice->save();
            }
        }
        return response()->json([
            'message'=>'Invoices Created',
            'status'=>true
        ],200);
    }

    public function updateInvoiceStatus(Request $request){

        $invoice = Invoice::find($request->invoice_id);
        
        if($invoice){

            $invoice->status = $request->status;
            $invoice->save();
            
            return response()->json([
                'message'=>'Status Updated',
                'status'=>true
            ],200);
    
        }
        return response()->json([
            'message'=>'Not found',
            'status'=>false
        ],200);

    }
}
