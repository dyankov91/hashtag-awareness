<?php
namespace Database\Migration\DynamoDB;

use Aws\DynamoDb\DynamoDbClient;
use QuanKim\LaravelDynamoDBMigrations\DBClient;

/**
 * Class CreateTableItemsArchive
 * @property DynamoDbClient dbClient
 */
class CreateTableItemsArchive extends DBClient
{
    public function up()
    {
        $this->dbClient->createTable([
            'TableName' => 'ItemsArchive',
            'KeySchema' => [
                [
                    'AttributeName' => 'Author',
                    'KeyType' => 'HASH',
                ],
                [
                    'AttributeName' => 'PublishedAt',
                    'KeyType' => 'RANGE',
                ],
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'Author',
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
            'TableName' => 'ItemsArchive',
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
            'TableName' => 'ItemsArchive',
        ]);

        $this->dbClient->waitUntil('TableNotExists', [
            'TableName' => 'ItemsArchive',
            '@waiter' => [
                'delay' => 5,
                'maxAttempts' => 20,
            ],
        ]);
    }
}