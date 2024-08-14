<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();


        $schedule->command('sync:zkteco')
            ->everyMinute()
            ->sendOutputTo(storage_path('logs/sync.log'))
            ->runInBackground();

        $schedule->command('route:hit')
            ->everyMinute()
            ->appendOutputTo(storage_path('logs/route_hit.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
