<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Helpers\ApiHelper;
use App\Coupon;
use DateTime;

class MemberShip extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $day;
    public $userid;

    public function __construct($day,$userid)
    {
        $this->day = $day;
        $this->userid = $userid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->day == 5)
        {
            return $this
            ->from('no-reply@activrespon.com', 'Activrespon')
            ->subject('Expired membership day -5')
            ->view('emails.membership.exp-membership-5')
            //->with($this->day);
           ;
        }
        elseif($this->day == 1)
        {
           $percent = 10;
           $duration = 3;
           $code_coupon = $this->generateCharacter();
           $coupon = $this->generate_coupon($percent,$duration,$this->userid,$code_coupon);

           $data_email = array(
              'percent'=>$percent,
              'duration'=>$duration,
              'coupon'=>$code_coupon,
           );

           return $this
            ->from('no-reply@activrespon.com', 'Activrespon')
            ->subject('Expired membership day -1')
            ->view('emails.membership.exp-membership-1')
            ->with('data',$data_email);
           ;
        } 
        elseif($this->day == -1)
        {

           return $this
            ->from('no-reply@activrespon.com', 'Activrespon')
            ->subject('Expired membership day +1')
            ->view('emails.membership.exp-membership_plus_1')
            //->with($this->day);
           ;
        }
  }

  public function generate_coupon($percent,$valid,$userid,$code_coupon)
  {
    $coupon = new Coupon;
    $coupon->package_id = 0;
    $coupon->user_id = $userid;
    $coupon->kodekupon = $code_coupon;
    $coupon->diskon_value = 0;
    $coupon->diskon_percent = $percent;
    $coupon->valid_until = new DateTime('+'.$valid.' days');
    $coupon->valid_to = '';
    $coupon->keterangan = "Kupon AutoGenerate Expired User H-1";
    $coupon->save();
  }

  public function generateCharacter()
  {
     $generate = $this->characterRandom();
     $coupon = Coupon::where("kodekupon","=",$generate)->first();

     if(is_null($coupon)){
          return $generate;
     } else {
          return $this->generateCharacter();
     }
  }

  public function characterRandom()
  {
    $karakter= 'abcdefghjklmnpqrstuvwxyz123456789';
    $string = 'special-'.substr(str_shuffle($karakter), 0, 8);
    return $string;
  }

/* END CLASS */
}
