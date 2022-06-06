<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Message;
use App\Order;
use App\PhoneNumber;
use App\Coupon;
use App\Helpers\ApiHelper;

use Carbon\Carbon;
use Mail, DB, Session, Auth;

use App\Jobs\SendNotif;

class Order extends Model
{
	/*
	* status 
	* 0 => created
	* 1 => confirmed bukti transfer, waiting admin response
	* 2 => paid
  * 3 => API order , user not paid yet
  * 4 => API order, paid
	*
	*	Mode 
	* 0 => simi
	* 1 => woowa
	*
	*	status_woowa 
	* 0 => not paid or full paid to woowa
	* 1 => invoice generated
	*
	* if package not basic -> order is more than 1 month
  *
	*
  *
  */
  protected $table = 'orders';
  protected $connection = 'mysql2';

	public static function create_order($data){
    //unique code 
    $unique_code = mt_rand(1, 1000);
		$user = $data['user'];
    $total = $data['price'] + $unique_code;
    $dt = Carbon::now();
    $chat_price = 0;

    if($data['upgrade'] <> null)
    {
      $grand_total = $data['upgrade'] + $chat_price + $unique_code;
    }
    else
    {
      $grand_total = $data['price'] + $data['priceupgrade'] - $data['diskon'] + $unique_code;
    }

    $order = new Order;
    // $str = 'ACT'.$dt->format('ymdHi');
    $str = 'ACT'.$dt->format('ymd').'-'.$dt->format('Hi');
    $order_number = Order::autoGenerateID($order, 'no_order', $str, 3, '0');
    $order->no_order = $order_number;
    $order->user_id = $user->id;
    $order->package =$data['namapaket'];
    $order->package_title =$data['namapakettitle'];

    //GENERATE INVOICE FROM COMMAND RESELLER:INVOICE, UNIQUE CODE NOT ADDING TO AVOID MISSVALUE
    if(isset($data['api']))
    {
      $order->total = $data['price'];
      $order->grand_total = $data['price'];
      $order->status = 1;
      $order->is_chat = 0;
      $order->save();

      $email_reseller = [
          'order' => $order,
          'user' => $user,
          'nama_paket' => $data['namapaket'],
          'no_order' => $order_number,
      ];

      if(env('APP_ENV') !== 'local'):
        Mail::send('emails.order', $email_reseller, function ($message) use ($order_number) {
          $message->from('no-reply@activrespon.com', 'Activrespon');
          $message->to('michaelsugih@gmail.com');
          $message->subject('[Activrespon] Order Nomor '.$order_number);
        });
      endif;

      return;
    }

    $order->coupon_id = $data['kuponid'];
    $order->total = $total;
    $order->total_upgrade = $data['priceupgrade'];
    $order->discount = $data['diskon'];
    $order->grand_total = $grand_total;
    // $order->grand_total = $data['price'] + $data['priceupgrade'] - $data['diskon'] + $unique_code;
    $order->status = 0;
    $order->is_chat = $data['chat'];
    $order->buktibayar = "";
    $order->keterangan = "";
    $order->status_woowa = 0;
    $mode = 0;
  
    $order->mode = 0;
    $order->month = $data['month'];
    $order->save();

    /*UPDATE COUPON STATUS*/
    if($data['reseller'] == true)
    {
      $coupon = Coupon::find($data['kuponid']);
      $coupon->user_id = Auth::id();
      $coupon->used = 1;
      $coupon->save();

      // SAVE USER RESELLER ID ACCORDING ON LINK COUPON
      // Note : if user register / login using coupon from another resller, then user will move into new reseller. ex : old reseller id = 1 then new reseller id = 2

      $user->reseller_id = $coupon->reseller_id;
      $user->save();
    }

    if($order->grand_total!=0){

      //mail order to user 
      $emaildata = [
          'order' => $order,
          'user' => $user,
          'nama_paket' => $data['namapaket'],
          'no_order' => $order_number,
      ];

      // WA MESSAGE
      if(env('APP_ENV') <> 'local')
      {
        $phone = $data['phone'];
        $message = null;
        $message .= '*Hi '.$user->name.'*,'."\n\n";
        $message .= "Berikut info pemesanan Activrespon :\n";
        $message .= '*No Order :* '.$order_number.''."\n";
        $message .= '*Nama :* '.$user->name.''."\n";
        $message .= '*Paket :* '.$data['namapaket'].''."\n";
        // $message .= '*Tgl Pembelian :* '.$dt->format('d-M-Y').''."\n";
        $message .= '*Total Biaya :*  Rp. '.str_replace(",",".",number_format($grand_total))."\n";

        $message .= "Silahkan melakukan pembayaran dengan bank berikut : \n\n";
        $message .= 'BCA (Sugiarto Lasjim)'."\n";
        $message .= '8290-336-261'."\n\n";
        
        $message .= "Harus diperhatikan juga, kalau jumlah yang di transfer harus *sama persis dengan nominal diatas* supaya _*kami lebih mudah memproses pembelianmu*_.\n\n";

        $message .= '*Sesudah transfer:*'."\n";
        $message .= '- *Login* ke https://activrespon.com'."\n";
        $message .= '- *Klik* Profile'."\n";
        $message .= '- Pilih *Order & Confirm*'."\n";
        $message .= '- *Upload bukti konfirmasi* disana'."\n\n";

        $message .= 'Terima Kasih,'."\n\n";
        $message .= 'Team Activrespon'."\n";
        $message .= '_*Activrespon is part of Activomni.com_';

        // ApiHelper::send_message_android(env('REMINDER_PHONE_KEY'),$message,$phone,'reminder');
        // ApiHelper::send_simi($phone,$message,env('REMINDER_PHONE_KEY'));
        // SendNotif::dispatch($phone,$message,env('REMINDER_PHONE_KEY'));
       /*  $admin = PhoneNumber::where('user_id',env('ADMIN_ID'))->first(); //admin
        $phone_key = $admin->device_key;
        $message_send = Message::create_message($phone,$message,$phone_key); */

        Mail::send('emails.order', $emaildata, function ($message) use ($user,$order_number) {
          $message->from('no-reply@activrespon.com', 'Activrespon');
          $message->to($user->email);
          $message->subject('[Activrespon] Order Nomor '.$order_number);
        });
      }

      //delete session order
      if(session::has('order'))
      {
        session::forget('order');
      }

      // delete session reseller_coupon_price
      if(session('reseller_coupon_price') <> null)
      {
        session::forget('reseller_coupon_price');
      } 

    }
    else {
			// for freemium case
    }
	}
	
  public static function autoGenerateID($model, $field, $search, $pad_length, $pad_string = '0')
  {
    $tb = $model->select(DB::raw("substr(".$field.", ".strval(strlen($search)+1).") as lastnum"))
								->whereRaw("substr(".$field.", 1, ".strlen($search).") = '".$search."'")
								->orderBy('id', 'DESC')
								->first();
		if ($tb == null){
			$ctr = 1;
		}
		else{
			$ctr = intval($tb->lastnum) + 1;
		}
		return $search.'-'.str_pad($ctr, $pad_length, $pad_string, STR_PAD_LEFT);
  }
}
