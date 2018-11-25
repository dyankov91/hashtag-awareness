<?php

namespace App\Console\Commands;

use App\Contracts\SocialMediaMonitoringInterface;
use Illuminate\Console\Command;
use App\Jobs\MonitorSocialMedia;

/**
 * Class MonitoringCommand
 */
class MonitoringCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoring:start 
                            {driver : Name of the Social Media Driver} 
                            {--k|keywords=* : Keywords for which to monitor.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitors a social media for specific keywords.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $driverAlias = $this->argument('driver');
        if (!app()->has($driverAlias)) {
            $this->error("Cannot find driver `{$driverAlias}` in the service container. " .
                "All drivers must be registered and aliased properly.");

            return false;
        }
        $driver = app($driverAlias);

        if (!$driver instanceof SocialMediaMonitoringInterface) {
            $this->error(sprintf(
                "Driver `%s` is not implementing the `%s` interface.",
                $driverAlias,
                SocialMediaMonitoringInterface::class
            ));

            return false;
        }

        $keywords = $this->option('keywords');
        if (!$keywords) {
            $keywords = [$this->ask('For what keyword we should monitor?')];
        }

        dispatch(new MonitorSocialMedia($driver, $keywords));
    }
}
