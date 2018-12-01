<?php

namespace App\Jobs;

use App\Item;
use Exception;
use Aws\Sdk as AwsSdk;
use Aws\DynamoDb\Marshaler;
use App\Events\ItemCreated;

/**
 * Class ProcessTwitterItem
 */
class ProcessTwitterItem extends Job
{
    /** @var array */
    protected $item;

    /**
     * ProcessTwitterFeed constructor.
     * @param array $item
     */
    public function __construct(array $item)
    {
        $this->item = $item;
    }

    /**
     * Normalizes the standard twitter object to Item DTO for storing
     * @param AwsSdk    $aws
     * @param Marshaler $marshaler
     */
    public function handle(AwsSdk $aws, Marshaler $marshaler)
    {
        try {
            $payload = $this->buildPayload();
            $dynamodb = $aws->createDynamoDb();
            $dynamodb->putItem([
                'TableName' => 'Items',
                'Item' => $marshaler->marshalJson(json_encode($payload)),
            ]);
            event(new ItemCreated($payload));
        } catch (Exception $e) {
            echo "Unable to add item:".PHP_EOL;
            echo $e->getMessage().PHP_EOL;
            die;
        }
    }

    /**
     * @return Item
     * @throws Exception
     */
    protected function buildPayload()
    {
        $author = $this->item['user']['name'];
        $text = $this->item['text'];
        $tags = $this->getTags();
        $publishedAt = $this->formatPublishedAt();
        
        return new Item(
            'twitter',
            $publishedAt,
            $author,
            $text,
            $tags
        );
    }

    /**
     * @return false|string
     */
    protected function formatPublishedAt()
    {
        return strtotime($this->item['created_at']);
    }

    /**
     * @return array
     */
    protected function getTags()
    {
        $tags = [];
        foreach ($this->item['entities']['hashtags'] as $tag) {
            $tags[] = $tag['text'];
        }

        return $tags;
    }

}
