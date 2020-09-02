<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\HelpersApiHelper;
use App\PhoneNumber;
use App\Order;
use App\WoowaOrder;
use App\InvoiceOrder;
use App\Server;
use App\Helpers\ApiHelper;

use Carbon\Carbon;

class CheckOrderWoowa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:orderwoowa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To check order woowa on db';

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
     * @return mixed
     */
    public function handle()
    {
      $orders = Order::
                where('mode',1)
                // ->where('status_woowa',1)
                ->where('month','>',1)
                ->get();
      foreach ($orders as $order){
        echo $order->id." ";
        $dt = Carbon::now();
        $selisih_bulan = $dt->diffInMonths(Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)) +1;
        echo $selisih_bulan;

        // $jumlahInvoiceOrder = InvoiceOrder::where("order_id",$order->id)->count();
        $jumlahWoowaOrder = WoowaOrder::where("order_id",$order->id)->count();
        if ($selisih_bulan>$jumlahWoowaOrder){
          $order->status_woowa = 0;
          $order->save();

          //create woowa orders
            $woowaOrder = new WoowaOrder;
            $woowaOrder->no_order = $order->no_order;
            $woowaOrder->label_month = $selisih_bulan." of ".$order->month;
            $woowaOrder->order_id = $order->id;
            $woowaOrder->user_id = $order->user_id;
            $woowaOrder->coupon_id = $order->coupon_id;
            $woowaOrder->package = $order->package;
            $woowaOrder->package_title = $order->package_title;
            $woowaOrder->total = $order->total;
            $woowaOrder->discount = $order->discount;
            $woowaOrder->grand_total = $order->grand_total;
            $woowaOrder->coupon_code = $order->coupon_code;
            $woowaOrder->coupon_value = $order->coupon_value;
            $woowaOrder->status = $order->status;
            $woowaOrder->buktibayar = $order->buktibayar;
            $woowaOrder->keterangan = $order->keterangan;
            $woowaOrder->status_woowa = 0;
            $woowaOrder->mode = $order->mode;
            $woowaOrder->month = $selisih_bulan;
            $woowaOrder->save();
          
          echo "in";
        }
        echo "\n";
      }
    }
}
