<?php

namespace App\Console\Commands;

use App\Jobs\Archive;
use Illuminate\Console\Command;

/**
 * Class ArchiveCommand
 */
class ArchiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archiver:work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive old Items data.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while (true) {
            $this->info( 'Dispatching archive job...');
            dispatch(new Archive);
            sleep(3);
        }
    }
}
