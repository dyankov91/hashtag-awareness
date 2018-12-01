<?php

namespace Database\Migration\DynamoDB;

use Aws\DynamoDb\DynamoDbClient;
use QuanKim\LaravelDynamoDBMigrations\DBClient;

/**
 * Class CreateTableItems
 * @property DynamoDbClient dbClient
 */
class CreateTableItems extends DBClient
{
    /**
     * Create
     */
    public function up()
    {
        $this->dbClient->createTable([
            'TableName' => 'Items',
            'KeySchema' => [
                [
                    'AttributeName' => 'Driver',
                    'KeyType' => 'HASH',
                ],
                [
                    'AttributeName' => 'PublishedAt',
                    'KeyType' => 'RANGE',
                ],
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'Driver',
                    'AttributeType' => 'S',
                ],
                [
                    'AttributeName' => 'PublishedAt',
                    'AttributeType' => 'N',
                ],
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 10,
                'WriteCapacityUnits' => 10
            ],
        ]);

        $this->dbClient->waitUntil('TableExists', [
            'TableName' => 'Items',
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
            'TableName' => 'Items',
        ]);

        $this->dbClient->waitUntil('TableNotExists', [
            'TableName' => 'Items',
            '@waiter' => [
                'delay' => 5,
                'maxAttempts' => 20,
            ],
        ]);
    }
}