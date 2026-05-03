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
    $schedule->command('tasks:cancel-missed')->dailyAt('00:01');
    $schedule->command('reminders:send')->everyMinute();
    $schedule->call(function () {
        \App\Models\Habit::query()->update(['reminder_sent_today' => false]);
    })->dailyAt('00:00');

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
