<?php

namespace App\Console;

use App\Console\Commands\ArchiveCommand;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\MonitoringCommand;
use App\Console\Commands\DynamoDb\CounterCommand;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CounterCommand::class,
        MonitoringCommand::class,
        ArchiveCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
