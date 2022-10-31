<?php

namespace App\Console;

use App\Console\Commands\RunHealthChecksWithTelegramCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Health\Commands\ScheduleCheckHeartbeatCommand;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(RunHealthChecksWithTelegramCommand::class)->everyMinute();
        $schedule->command('telescope:prune --hours=168')->daily(); // 1 week for telescope
        $schedule->command('model:prune')->daily();

        $schedule->command(ScheduleCheckHeartbeatCommand::class)->everyMinute();
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