<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\Reseller;
use App\Phoneapis;
use Carbon\Carbon;
use App\Http\Controllers\ApiUserController as apiuser;

class ResellerInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reseller:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate invoice for reseller';

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
        // $invoice_period = Carbon::now()->subMonth(0)->format('m-Y'); //demo only
        $invoice_period = Carbon::now()->subMonth(1)->format('m-Y');
        $phone_api = Phoneapis::where('is_delete',0)->get();
        
        if($phone_api->count() > 0)
        {
          foreach($phone_api as $row):
            $id = $row->id;
            $reseller = Reseller::where([['period',$invoice_period],['phone_api_id',$id]])->first();
            $package_check = apiuser::package_list($row->package); //get package

            if(is_null($reseller))
            {
              $inv = new Reseller;
              $inv->user_id = $row->user_id;
              $inv->phone_api_id = $id;
              $inv->package = $row->package;
              $inv->total = $package_check['price'];
              $inv->period = $invoice_period;
              $inv->save();
            }
          endforeach;
        }
    }
}
