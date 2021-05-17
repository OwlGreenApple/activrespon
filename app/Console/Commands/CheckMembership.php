<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\PhoneNumber;
use App\Membership;
use App\Message;
use App\WebHookWA;
use App\Mail\MemberShip as EmailMember;
use App\Helpers\ApiHelper;
use App\Helpers\WamateHelper;

use App\Jobs\SendNotif;

class CheckMembership extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:membership';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Membership Valid Until';

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
        $users = User::where('day_left','>',-2)->get();
        $membership = $remain_day_left = 0;
        $phone = null;

        if($users->count() > 0)
        {
            foreach($users as $row):

              $day_left = $row->day_left;
              $user_id = $row->id;

              $membership = Membership::where([['user_id',$user_id],['status','>',0]])->get();
              $membership = $membership->count();
              $user = User::find($user_id);

              if($day_left == 0 && $membership > 0)
              {
                 continue;
              }

              if($user->day_left > -2)
              {
                 $user->day_left--;
                 $user->save();
                 $remain_day_left = $user->day_left;
              }

              if($user->is_chat > 0)
              {
                 $user->is_chat--;
                 $user->save();
              }

              if($membership == 0 && $remain_day_left <= 0)
              {
                 $user->membership = null;
                 $user->status = 0;
                 $user->reseller_token = null;
                 $user->save();
                 $phone = PhoneNumber::where('user_id',$row->id)->first();
              }

              $mode = null;
              if(!is_null($phone)){
                 $mode = $phone->mode;
              }

              if($mode == 2):
                $result = WamateHelper::delete_devices($phone->wamate_id,$user->token,$phone->ip_server);
                $email_wamate = env('APP_ENV')."-".$user->id."@y.com";
                $countwebhook = WebHookWA::where('device_id',$phone->wamate_id);
                
                if($countwebhook->get()->count() > 0)
                {
                  WebHookWA::where('device_id',$phone->wamate_id)->delete();
                }

                $phone->delete();
                // $result = json_decode(WamateHelper::login($email_wamate),true);
                $own = User::find($user->id);
                $own->token = null;
                $own->refresh_token = null;
                $own->save();

                /*if(isset($result['token']))
                {
                  
                }*/
              
              elseif($mode <> null):
                $phone->counter = 0;
                $phone->max_counter = 0;
                $phone->max_counter_day = 0;
                $phone->status = 0;
                $phone->save();
              endif;

              if ($day_left == 5) {
                $message = null;
                $message .= "*Selamat ".$user->name.",* \n\n";
                $message .= "Gimana kabarnya? \n \n";
                $message .= "Kami mau kasih tau kalau *waktu berlangganan kamu akan habis 5 hari lagi*. \n \n";
                $message .= "Jangan sampai kamu _kehabisan waktu berlangganan saat menggunakan Activrespon_ yah \n \n";
                $message .= "Kamu bisa langsung perpanjang dengan klik link dibawah ini \n";
                $message .= "*►https://activrespon.com/dashboard/pricing* \n \n";

                $message .= "_Oh iya, kalau kamu pertanyaan jangan ragu untuk menghubungi kami di_  \n";
                $message .= "*WA 0817-318-368* \n\n";

                $message .= 'Terima Kasih,'."\n\n";
                $message .= 'Team Activrespon'."\n";
                $message .= '_*Activrespon is part of Activomni.com_';

                // SendNotif::dispatch($user->phone_number,$message,env('REMINDER_PHONE_KEY'));
                $message_send = Message::create_message($user->phone_number,$message,env('REMINDER_PHONE_KEY'));
              }
              else if ($day_left == 1) {
                $message = null;
                $message .= "Gawat ".$user->name."!, \n\n";
                $message .= "*Waktu berlangganan Activresponmu tinggal satu hari*. \n \n";
                $message .= "*Perpanjang sekarang juga*, _sebelum waktu berlanggananmu habis ditengah jalan saat menggunakan Activrespon._ \n\n";
                $message .= "Klik Sekarang di *►https://activrespon.com/dashboard/pricing* \n\n";
                $message .= 'Terima Kasih,'."\n\n";
                $message .= 'Team Activrespon'."\n";
                $message .= '_*Activrespon is part of Activomni.com_';

                // SendNotif::dispatch($user->phone_number,$message,env('REMINDER_PHONE_KEY'));
                $message_send = Message::create_message($user->phone_number,$message,env('REMINDER_PHONE_KEY'));
              }
              else if ($day_left == 0) {
                $message = null;
                $message .= "*Waktu berlangganan Activrespon-mu habis loh ".$user->name.",* \n\n";
                $message .= "_Perpanjang sekarang juga, supaya kamu bisa gunakan kembali akun Activresponmu._ \n \n";
                $message .= "Klik disini untuk perpanjang \n";
                $message .= "*►https://activrespon.com/dashboard/pricing* \n \n";
                $message .= "Oh iya, kamu juga *bisa dapetin Special Voucher Activrespon* dengan klik link dibawah ini. \n";
                $message .= "*►https://bit.ly/claim-special-voucher* \n \n";
                $message .= 'Terima Kasih,'."\n\n";
                $message .= 'Team Activrespon'."\n";
                $message .= '_*Activrespon is part of Activomni.com_';

                // SendNotif::dispatch($user->phone_number,$message,env('REMINDER_PHONE_KEY'));
                $message_send = Message::create_message($user->phone_number,$message,env('REMINDER_PHONE_KEY'));
              }
              else if ($day_left == -1) {
                $message = null;
                $message .= "*Hi ".$user->name.",* \n\n";
                $message .= "Kami mau mengingatkan kalau *waktu berlangganan kamu sudah habis sejak kemarin*. \n \n";
                $message .= "_Jangan sampai hubungan dengan klienmu jadi terhambat karena waktu berlangganan yang habis ya_ \n \n";
                $message .= "*Klik sekarang di ► https://activrespon.com/dashboard/pricing* \n \n";
                $message .= "Kalau ada pertanyaan, jangan sungkan menghubungi kami di \n";
                $message .= "*WA 0817-318-368* \n \n";
                
                $message .= 'Terima Kasih,'."\n\n";
                $message .= 'Team Activrespon'."\n";
                $message .= '_*Activrespon is part of Activomni.com_';

                // SendNotif::dispatch($user->phone_number,$message,env('REMINDER_PHONE_KEY'));
                $message_send = Message::create_message($user->phone_number,$message,env('REMINDER_PHONE_KEY'));
              }
              
              if(($day_left == 5 || $day_left == 1 || $day_left == -1) )
              {
                if(env('APP_ENV') <> 'local'){
                 Mail::to($row->email)->send(new EmailMember($day_left,$row->id));
                }
              }
            endforeach;
        }
    }
}
