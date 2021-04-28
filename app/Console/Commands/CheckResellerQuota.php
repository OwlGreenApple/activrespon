<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Phoneapis;
use App\Http\Controllers\ApiUserController;

class CheckResellerQuota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:quota';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To check api user reach 30 days from join, then quota will be renew';

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
        $getuser = Phoneapis::whereRaw('DATEDIFF(NOW(), DATE(updated_at)) >= 30 AND is_delete = 0')->get();
        $package = new ApiUserController;
        
        if($getuser->count() > 0):
          foreach($getuser as $row)
          {
            $phone_api = Phoneapis::find($row->id);
            $phone_api->quota = $package->package_list($row->package)['quota'];
            $phone_api->save();
          }
        endif;
    }
}
