<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Helpers\ApiHelper;
use App\Helpers\Alert;

class NotifyOrder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $day;
    public $orders;
    public function __construct($day,$orders)
    {
        $this->day = $day;
        $this->orders = $orders;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if($this->day == 1)
        {
            $orders = $this->orders;

            $data_email = [
              'no'=>$orders["no"],
              'package'=>$orders["package"],
              'price'=>Alert::pricing($orders["pack"]),
              'disc'=>$orders["discount"],
              'total'=>$orders["total"],
            ];

            return $this
              ->from('no-reply@activrespon.com', 'Activrespon')
              ->subject('Your order confirmation day 1')
              ->view('emails.notify.notif-order')
              ->with('data',$data_email);
             ;
        }
        elseif($this->day == 5)
        {
            $orders = $this->orders;

            $data_email = [
              'no'=>$orders["no"],
              'package'=>$orders["package"],
              'price'=>Alert::pricing($orders["pack"]),
              'disc'=>$orders["discount"],
              'total'=>$orders["total"],
            ];

            return $this
              ->from('no-reply@activrespon.com', 'Activrespon')
              ->subject('Your order confirmation day 5')
              ->view('emails.notify.notif-order-2')
              ->with('data',$data_email);
             ;
        }
        
    }
}
