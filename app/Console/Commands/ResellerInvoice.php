<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\Reseller;
use App\Phoneapis;
use App\User;
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

        //GENERATE INVOICE ON ORDER
        $invoice = Reseller::where([['period',$invoice_period]])
                    ->selectRaw('SUM(total) AS gt, user_id')
                    ->groupBy('user_id')
                    ->get();

        if($invoice->count() > 0)
        {
          foreach($invoice as $row):
            $user = User::find($row->user_id);
            $pckg = [
              'namapaket'=>'WA Reseller - '.$invoice_period,
              'namapakettitle'=>'WA Reseller',
              'user'=>$user,
              'price'=>$row->gt,
              'priceupgrade'=>0,
              'diskon'=>0,
              'upgrade'=>null,
              'api'=>true
            ];

            Order::create_order($pckg);
          endforeach;
        }

        
    }
}
