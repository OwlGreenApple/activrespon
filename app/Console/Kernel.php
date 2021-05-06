<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendMessage::class,
        Commands\CheckCounter::class,
        Commands\GetKey::class,
        Commands\ResetMessageCounter::class,
        Commands\QueueMessage::class,
        Commands\notifOrder::class,
        Commands\CheckOrderWoowa::class,
        Commands\ResetServersimi::class,
        Commands\QueueCampaign::class,
        Commands\ClearCache::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      if (env("APP_ENV")=="production") {
        $schedule->command('check:orderwoowa')->dailyAt('01:00');
        $schedule->command('check:package')->dailyAt('00:01');
        $schedule->command('check:membership')->dailyAt('01:00');
        $schedule->command('notif:order')->dailyAt('08:00');
        $schedule->command('reset:message')->dailyAt('01:00');
        $schedule->command('get:key')->everyMinute();
        $schedule->command('check:counter')->everyMinute();
        $schedule->command('check:connection')->everyFifteenMinutes();
        // $schedule->command('check:wa')->hourly();
        $schedule->command('reset:serversimi')->everyFifteenMinutes();
        $schedule->command('check:deviceapi')->everyTenMinutes();
        // $schedule->command('reseller:invoice')->monthlyOn(1, '06:00');;
        $schedule->command('check:quota')->dailyAt('00:05');
      }
      if (env("APP_ENV")=="automation") {
        $schedule->command('queue:campaign')->everyMinute();
        $schedule->command('queue:message')->everyMinute(); 
        $schedule->command('send:message')->everyMinute();
      }
      $schedule->command('clear:cache')->dailyAt('05:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
