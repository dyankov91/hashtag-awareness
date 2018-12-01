<?php

namespace App\Console\Commands\DynamoDb;

use Aws\Sdk;
use Aws\DynamoDb\Marshaler;
use Illuminate\Console\Command;

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
     * @param Sdk       $aws
     * @param Marshaler $marshaler
     */
    public function handle(Sdk $aws, Marshaler $marshaler)
    {
        $table = $this->argument('table');

        $dynamoDB = $aws->createDynamoDb();
        $result = $dynamoDB->getItem([
            "ConsistentRead" => true,
            'TableName' => 'Counters',
            'Key' => $marshaler->marshalJson(json_encode(['CountedTable' => $table])),
            'AttributesToGet' => ['CountItems'],
        ]);
        
        $count = $result->search('Item.CountItems') ?
            $marshaler->unmarshalValue($result->search('Item.CountItems'))
            : 0;

        $this->info(sprintf(
            "Table `%s` has '%s' records.",
            $table,
            $count
        ));
    }
}
