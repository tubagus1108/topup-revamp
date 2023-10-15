<?php

namespace App\Console;

use App\Console\Commands\DigiflazzCommand;
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
        $schedule->command('digiflazz:run')
            ->everyFiveMinutes();

        $schedule->command('app:ovopay-run')
            ->everyThreeMinutes();

        $schedule->command('app:gopay-run')
            ->everyThreeMinutes();
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
