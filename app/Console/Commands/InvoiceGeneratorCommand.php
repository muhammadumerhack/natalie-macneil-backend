<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Verification;
use App\Models\Invoice;
use Carbon\Carbon;

class InvoiceGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:generator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will generate monthly invoices of all verifiers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

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
        // info("Created");
        return 0;
    }
}
