<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Phoneapis;
use App\Helpers\WamateHelper;

class CheckAPIUserDeviceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:deviceapi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check api user device status';

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
     * @return int
     */
    public function handle()
    {
        $phoneapi = Phoneapis::where('is_delete','=',0)->get();
        if($phoneapi->count() > 0)
        {
          foreach($phoneapi as $row):
            $token = $row->token;
            $device_id = $row->device_id;
            $reseller_ip = $row->ip_server;
            $api = WamateHelper::show_device($token,$device_id,$reseller_ip);
            $api = json_decode($api,true);

            $device = Phoneapis::find($row->id);
            if($api['status'] == 'PAIRED')
            {
              $device->device_status = 1;
            }
            else
            {
              $device->device_status = 0;
            }
            $device->save();
          endforeach;
        }
    }
}
