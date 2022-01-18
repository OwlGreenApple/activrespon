<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OTPEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $code;
    public $name;

    public function __construct($code,$name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->from('no-reply@activrespon.com', 'Activrespon')
        ->subject('OTP sambungan ke WA')
        ->view('emails.OTPEmail')
        ->with([
          'code' => $this->code,
          'name' => $this->name,
        ]);
    }
}
