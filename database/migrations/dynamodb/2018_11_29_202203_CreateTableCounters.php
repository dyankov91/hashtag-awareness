<?php
namespace Database\Migration\DynamoDB;

use Aws\DynamoDb\DynamoDbClient;
use QuanKim\LaravelDynamoDBMigrations\DBClient;

/**
 * Class CreateTableCounters
 * @property DynamoDbClient dbClient
 */
class CreateTableCounters extends DBClient
{
    public function up()
    {
        $this->dbClient->createTable([
            'TableName' => 'Counters',
            'KeySchema' => [
                [
                    'AttributeName' => 'CountedTable',
                    'KeyType' => 'HASH',
                ],
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'CountedTable',
                    'AttributeType' => 'S',
                ],
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 10,
                'WriteCapacityUnits' => 10
            ],
        ]);

        $this->dbClient->waitUntil('TableExists', [
            'TableName' => 'Counters',
            '@waiter' => [
                'delay' => 5,
                'maxAttempts' => 20,
            ],
        ]);
    }

    /**
     * if cannot rollback set $canRollback = false
     */
    public function down(&$canRollback)
    {
        $this->dbClient->deleteTable([
            'TableName' => 'Counters',
        ]);

        $this->dbClient->waitUntil('TableNotExists', [
            'TableName' => 'Counters',
            '@waiter' => [
                'delay' => 5,
                'maxAttempts' => 20,
            ],
        ]);
    }
}