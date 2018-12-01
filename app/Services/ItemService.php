<?php

namespace App\Services;

use Aws\Sdk;
use App\Item;
use Exception;
use Aws\DynamoDb\Marshaler;
use App\Events\ItemCreated;
use App\Events\ItemDeleted;
use Aws\DynamoDb\DynamoDbClient;
use Illuminate\Support\Collection;

/**
 * Class ItemService
 */
class ItemService
{
    /** @var Sdk */
    protected $aws;

    /** @var DynamoDbClient */
    protected $dynamoDb;

    /** @var Marshaler */
    protected $marshaler;

    /**
     * ItemService constructor.
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
     * @param Item $item
     */
    public function storeItem(Item $item)
    {
        try {
            $this->dynamoDb->putItem([
                'TableName' => 'Items',
                'Item' => $this->marshaler->marshalJson(json_encode($item)),
            ]);
            event(new ItemCreated($item));
        } catch (Exception $e) {
            echo "Unable to store item:".PHP_EOL;
            echo $e->getMessage().PHP_EOL;
            die;
        }
    }

    /**
     * @param int $count
     * @return Collection
     * @throws Exception
     */
    public function getLastItems(int $count): Collection
    {
        // @TODO handle pagination (only 1MB of data per page is returned)
        $result = $this->dynamoDb->scan([
            'ConsistentRead' => true,
            'TableName' => 'Items',
            'Limit' => $count,
            'ScanIndexForward' => false,
        ]);

        $items = $result->toArray()['Items'];

        $collection = new Collection();
        foreach ($items as $item) {
            $item = new Item($this->marshaler->unmarshalItem($item));
            $collection->push($item);
        }

        return $collection;
    }

    /**
     * @param Collection|Item[] $items
     * @throws Exception
     */
    public function bulkDelete(Collection $items): void
    {
        foreach ($items as $item) {
            $this->dynamoDb->deleteItem([
                'TableName' => 'Items',
                'Key' => $this->marshaler->marshalJson(json_encode($item->getKey())),
            ]);
            event(new ItemDeleted($item));
        }
    }
}
