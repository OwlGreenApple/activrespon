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
        $invoice_period = Carbon::now()->subMonth(1)->format('m-Y'); //demo only set to 0
        //GENERATE INVOICE ON ORDER
        $invoice = Reseller::where([['period',$invoice_period]])
                    ->selectRaw('SUM(total) AS gt, reseller_id')
                    ->groupBy('reseller_id')
                    ->get();

        if($invoice->count() > 0)
        {
          foreach($invoice as $row):
            $user = User::find($row->reseller_id);
            $pckg = [
              'namapaket'=>'WA Reseller - '.$invoice_period.'-'.$row->reseller_id,
              'namapakettitle'=>$invoice_period,
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

/*end class*/
}
