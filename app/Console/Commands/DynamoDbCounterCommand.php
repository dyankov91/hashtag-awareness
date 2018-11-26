<?php

namespace App\Console\Commands;

use Aws\Sdk;
use Illuminate\Console\Command;

/**
 * Class CounterCommand
 */
class DynamoDbCounterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamodb:count 
                            {table : Name of the table which records to count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Return a count of items in dynamodb table.';

    /**
     * Execute the console command.
     * @param Sdk $aws
     */
    public function handle(Sdk $aws)
    {
        $table = $this->argument('table');

        $dynamoDB = $aws->createDynamoDb();
        $result = $dynamoDB->scan(['TableName' => $table, 'Select' => 'COUNT']);
        
        $this->info(sprintf(
            "Table `%s` has '%s' items.",
            $table,
            $result->get('Count')
        ));
    }
}
