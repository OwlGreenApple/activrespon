<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PhoneNumber;
use App\User;
use Carbon\Carbon;

class ClearCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To clear cache';

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
      exec('php artisan cache:clear');
      exec('php artisan view:clear');
      exec('php artisan config:clear');
    }

/* End check counter */
}
