<?php

namespace App\Console\Commands\DynamoDb;

use Illuminate\Console\Command;
use App\Services\CounterService;

/**
 * Class CounterCommand
 */
class CounterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamodb:count {table : Name of the table which records to count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Return a count of items in dynamodb table.';

    /**
     * Execute the console command.
     * @param CounterService $counterService
     */
    public function handle(CounterService $counterService)
    {
        $table = $this->argument('table');
        $count = $counterService->getCountFor($table);

        $this->info(sprintf(
            "Table `%s` has '%s' records.",
            $table,
            $count
        ));
    }
}
