<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Account;
use App\HistorySearch;
use App\User;
use App\Group;
use App\Save;
use App\Coupon;
use App\Invoice;
use App\InvoiceOrder;
use App\Order;
use App\WoowaOrder;
use App\UserLog;
use App\Notification;
use App\Ads;
use App\AdsHistory;

use App\Helpers\Helper;
use Carbon\Carbon;
use Crypt;
use Auth,Mail,Validator,Storage,DateTime;

class WooWAController extends Controller
{   
  public function load_woowa(Request $request){
    //halaman list order admin woowa
    $orders = WoowaOrder::
								// join(env('DB_DATABASE').'.users','orders.user_id','users.id')->
								where('woowa_orders.mode',1) // mode woowa
								->where('status',2) // paid
								->where('status_woowa',0)
                ->select('woowa_orders.*')
                ->orderBy('created_at','desc')
                ->orderBy('status_woowa','asc')
                ->get();
		$totaltagihan=0;
		foreach ($orders as $order) {
			// $totaltagihan += ($order->grand_total / $order->month);
			$totaltagihan += 125000;
		}
    $arr['view'] = (string) view('admin.list-woowa.content')
                      ->with([
												'orders'=>$orders,
												'totaltagihan'=>$totaltagihan,
											]);
    return $arr;
  }


  public function create_invoice(Request $request){
		$dt = Carbon::now();

		$invoice = new Invoice;
    // $str = 'IWW'.$dt->format('ymdHi');
    $str = 'IWW'.$dt->format('ymd').'-'.$dt->format('Hi');
    $invoice_number = Order::autoGenerateID($invoice, 'no_invoice', $str, 3, '0');
    $invoice->no_invoice = $invoice_number;
    $invoice->status = 0;
    $invoice->buktibayar = "";
    $invoice->keterangan = "";
    $invoice->total = 0;
		$invoice->save();

    //halaman list order admin woowa
    $orders = WoowaOrder::
								// join(env('DB_DATABASE').'.users','orders.user_id','users.id')->
								where('mode',1) // mode woowa
								->where('status',2) // paid
								->where('status_woowa',0)
                ->where('no_order',"not like","%testing%")
                ->select('woowa_orders.*')
                ->orderBy('created_at','desc')
                ->orderBy('status_woowa','asc')
                ->get();
		$totaltagihan=0;
		foreach ($orders as $order) {
			// $totaltagihan += ($order->grand_total / $order->month);
      $totaltagihan += 125000;

			$invoiceorder = new InvoiceOrder;
			$invoiceorder->invoice_id = $invoice->id;
			$invoiceorder->order_id = $order->id;
			$invoiceorder->save();
			
			$order->status_woowa = 1;
			$order->save();
		}

    $invoice->total = $totaltagihan;
		$invoice->save();

    $arr['status'] = 'success';
    $arr['message'] = 'Invoice berhasil dibuat';

    return $arr;
  }

  
  public function load_invoice(Request $request){
    //halaman list order admin
    $user = Auth::user();
    $invoices = Invoice::
                orderBy('created_at','desc')
                ->get();
    $arr['view'] = (string) view('admin.list-woowa-invoice.content')
                      ->with([
                        'invoices'=>$invoices,
                        'user'=>$user,
                      ]);
    return $arr;
  }
  
  //klo dilunasi lewat admin page woowa
  public function confirm_invoice(Request $request){
    //konfirmasi pembayaran admin
    $invoice = Invoice::find($request->id_confirm);
    
    if($invoice->status==0)
    {
      $invoice->status = 1;

      if($request->hasFile('buktibayar'))
      {
        // $path = Storage::putFile('bukti',$request->file('buktibayar'));
        $dir = 'woowa_bukti_bayar';
        $filename = $invoice->no_invoice.'.jpg';
        Storage::disk('s3')->put($dir."/".$filename, file_get_contents($request->file('buktibayar')), 'public');
        $invoice->buktibayar = $dir."/".$filename;
        
      } else {
        $arr['status'] = 'error';
        $arr['message'] = 'Upload file buktibayar terlebih dahulu';
        return $arr;
        // $pathUrl = str_replace(url('/'), '', url()->previous());
        // return redirect($pathUrl)->with("error", "Upload file buktibayar terlebih dahulu");
      }  
      $invoice->keterangan = $request->keterangan;
      $invoice->save();

    } else {
      $arr['status'] = 'error';
      $arr['message'] = 'invoice telah atau sedang dikonfirmasi oleh admin';
			return $arr;
        // $pathUrl = str_replace(url('/'), '', url()->previous());
        // return redirect($pathUrl)->with("error", "Order telah atau sedang dikonfirmasi oleh admin.");
    }
		
		
    $arr['status'] = 'success';
    $arr['message'] = 'Invoice berhasil dikonfirmasi';
    return $arr;
  }

  public function load_invoice_order(Request $request){
    //halaman list order admin
    $orders = InvoiceOrder::
								join("woowa_orders","woowa_orders.id","=","invoice_orders.order_id")
								->where("invoice_id",$request->id)
								->select("woowa_orders.*")
                ->orderBy('created_at','desc')
                ->get();
    $arr['view'] = (string) view('admin.list-woowa-invoice.content-detail')
                      ->with('orders',$orders);
    return $arr;
  }
  

/* end class */
}
