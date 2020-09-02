<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\HelpersApiHelper;
use App\PhoneNumber;
use App\Server;
use App\Helpers\ApiHelper;

class TestCurl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:curl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To Curl manually';

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
      $curl = curl_init();

      $data = array(
          'customer_phone'=>"+628123238793",
          'message'=>"coba 112233 rizky",
          'key_woowa'=>"123a17b0120d516ede400554a5d928d714491147f365fa7f",
      );

		  $url = "https://activrespon.com/dashboard/send-message-automation";

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);
      return $response;
    }
}
