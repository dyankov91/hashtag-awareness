<?php

namespace App\Console\Commands;

use App\Jobs\DetectAnomalies;
use Illuminate\Console\Command;

/**
 * Class AnomalyDetectorCommand
 */
class AnomalyDetectorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anomalies:detect
                                {--k|keyword= : Keyword for which to detect anomalies in the data sets.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Examine data for anomalies.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keyword = $this->option('keyword');
        if (!$keyword) {
            $keyword = $this->ask('For what keyword we should inspect?');
        }

        dispatch(new DetectAnomalies($keyword));
    }
}
