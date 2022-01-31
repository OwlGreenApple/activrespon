<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyOrder;
use App\User;
use App\Order;
use App\Message;
use App\PhoneNumber;
use Carbon\Carbon;
use Date;
use App\Helpers\ApiHelper;

use App\Jobs\SendNotif;

class notifOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To notify user to make payment after order';

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
        $orders = Order::join(env('DB_DATABASE').'.users as db1','orders.user_id','db1.id')
									->where([['db1.status','=',0],['orders.status','=',0]])
									->select('orders.*','db1.email','db1.phone_number')
									->get();

        $admin = PhoneNumber::where('user_id',env('ADMIN_ID'))->first(); //admin
        $phone_key = $admin->device_key;

        if($orders->count() > 0)
        {
          foreach($orders as $row)
          {
            $diffDay = Carbon::now()->diffInDays(Carbon::parse($row->created_at));
            echo $row->phone_number."-".$diffDay;
            $user = User::find($row->user_id);
            if ($diffDay == 1){
              $message = null;
              $message .= '*Hi '.$user->name.'*,'."\n\n";
              $message .= "_Gimana kabarnya?_ \n";
              $message .= "Kami mau mengingatkan nih kalau kamu *belum melakukan transfer dan konfirmasi pembayaran*. \n \n";
              $message .= "_Kemarin kamu sudah membeli paket Activrespon, ini rinciannya :_ \n \n";
              $message .= '*No Order :* '.$row->no_order.''."\n";
              $message .= '*Nama :* '.$user->name.''."\n";
              $message .= '*Paket :* '.$row->package_title.''."\n";
              $message .= '*Total Biaya :*  Rp. '.str_replace(",",".",number_format($row->total))."\n";

              $message .= "Silahkan melakukan pembayaran dengan bank berikut : \n\n";
              $message .= 'BCA (Sugiarto Lasjim)'."\n";
              $message .= '8290-336-261'."\n\n";
              
              $message .= "_Buruan transfer dan konfirmasi agar pembelianmu tidak dihapus oleh sistem._\n\n";

              $message .= '*Sesudah transfer:*'."\n";
              $message .= '- *Login* ke https://activrespon.com'."\n";
              $message .= '- *Klik* Profile'."\n";
              $message .= '- Pilih *Order & Confirm*'."\n";
              $message .= '- *Upload bukti konfirmasi* disana'."\n\n";

              $message .= 'Terima Kasih,'."\n\n";
              $message .= 'Team Activrespon'."\n";
              $message .= '_*Activrespon is part of Activomni.com_';

              // SendNotif::dispatch($user->phone_number,$message,env('REMINDER_PHONE_KEY'));
              $message_send = Message::create_message($user->phone_number,$message,$phone_key);
            }
            else if ($diffDay == 5){
              $message = null;
              $message .= '*Hi '.$user->name.'*,'."\n\n";
              $message .= "*Yakin bisa rela?* Hari ini kamu *bakal kehilangan harga spesial* yang sudah kamu dapatkan 2 hari lalu ketika order Activrespon lhoo. \n \n";
              $message .= "_Ini rinciannya :_ \n \n";
              $message .= '*No Order :* '.$row->no_order.''."\n";
              $message .= '*Nama :* '.$user->name.''."\n";
              $message .= '*Paket :* '.$row->package_title.''."\n";
              $message .= '*Total Biaya :*  Rp. '.str_replace(",",".",number_format($row->total))."\n";

              $message .= "Silahkan melakukan pembayaran dengan bank berikut : \n\n";
              $message .= 'BCA (Sugiarto Lasjim)'."\n";
              $message .= '8290-336-261'."\n\n";
              
              $message .= "Buruan transfer dan konfirmasi sekarang karena kalau tidak, _pembelian mu akan dihapus jam 12 malam nanti oleh sistem_. *Kamu juga akan kehilangan kesempatan memiliki Activrespon dengan harga spesial.* \n\n";

              $message .= '*Sesudah transfer:*'."\n";
              $message .= '- *Login* ke https://activrespon.com'."\n";
              $message .= '- *Klik* Profile'."\n";
              $message .= '- Pilih *Order & Confirm*'."\n";
              $message .= '- *Upload bukti konfirmasi* disana'."\n\n";

              $message .= 'Terima Kasih,'."\n\n";
              $message .= 'Team Activrespon'."\n";
              $message .= '_*Activrespon is part of Activomni.com_';

              // SendNotif::dispatch($user->phone_number,$message,env('REMINDER_PHONE_KEY'));
              $message_send = Message::create_message($user->phone_number,$message,$phone_key);
            }
           
            if($diffDay == 1 || $diffDay == 5)
            {
                $orders = [
                  'no'=>$row->no_order,
                  'package'=>$row->package_title,
                  'pack'=>$row->package,
                  'discount'=>$row->discount,
                  'total'=>$row->grand_total,
                ];
                Mail::to($row->email)->send(new NotifyOrder($diffDay,$orders));
            }
            sleep(2);
          } // END FOREACH
        }// END IF
    }
}
