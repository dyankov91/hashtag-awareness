<?php

namespace App\Services;

use Aws\Sdk;
use Exception;
use Aws\DynamoDb\Marshaler;
use Aws\DynamoDb\DynamoDbClient;
use Illuminate\Support\Collection;

/**
 * Class ArchiverService
 */
class ArchiverService
{
    /** @var Sdk */
    protected $aws;

    /** @var DynamoDbClient */
    protected $dynamoDb;

    /** @var Marshaler */
    protected $marshaler;

    /**
     * ArchiverService constructor.
     * @param Sdk       $aws
     * @param Marshaler $marshaler
     */
    public function __construct(Sdk $aws, Marshaler $marshaler)
    {
        $this->aws = $aws;
        $this->dynamoDb = $aws->createDynamoDb();
        $this->marshaler = $marshaler;
    }

    /**
     * @param Collection $items
     * @throws Exception
     */
    public function bulkArchive(Collection $items): void
    {
        foreach ($items as $item) {
            $this->dynamoDb->putItem([
                'TableName' => 'ItemsArchive',
                'Item' => $this->marshaler->marshalJson(json_encode($item)),
            ]);
        }
    }
}
