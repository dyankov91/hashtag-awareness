<?php

namespace Database\Migration\DynamoDB;

use Aws\DynamoDb\DynamoDbClient;
use QuanKim\LaravelDynamoDBMigrations\DBClient;

/**
 * Class CreateSocialMediaItems
 * @property DynamoDbClient dbClient
 */
class CreateSocialMediaItems extends DBClient
{
    /**
     * Create
     */
    public function up()
    {
        $this->dbClient->createTable([
            'TableName' => 'SocialMediaItems',
            'KeySchema' => [
                [
                    'AttributeName' => 'Id',
                    'KeyType' => 'HASH',
                ],
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'Id',
                    'AttributeType' => 'S',
                ],
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 10,
                'WriteCapacityUnits' => 10
            ],
        ]);

        $this->dbClient->waitUntil('TableExists', [
            'TableName' => 'SocialMediaItems',
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
            'TableName' => 'SocialMediaItems',
        ]);

        $this->dbClient->waitUntil('TableNotExists', [
            'TableName' => 'SocialMediaItems',
            '@waiter' => [
                'delay' => 5,
                'maxAttempts' => 20,
            ],
        ]);
    }
}