<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisteredEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $password;
    public $name;

    public function __construct($password,$name)
    {
        $this->name = $name;
        $this->password = $password;
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
        ->subject($this->subject)
        ->view('emails.RegisteredEmail')
        ->with([
          'password' => $this->password,
          'name' => $this->name,
        ]);
    }
}
