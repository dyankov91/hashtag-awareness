<?php

namespace App\Jobs;

use Exception;
use Aws\Sdk as AwsSdk;
use App\SocialMediaItem;
use Aws\DynamoDb\Marshaler;

/**
 * Class ProcessTwitterFeedItem
 */
class ProcessTwitterFeedItem extends Job
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
     * Normalizes the standard twitter feed item to SocialMediaItem DTO for storing
     * @param AwsSdk    $aws
     * @param Marshaler $marshaler
     */
    public function handle(AwsSdk $aws, Marshaler $marshaler)
    {
        try {
            $dynamodb = $aws->createDynamoDb();
            $dynamodb->putItem([
                'TableName' => 'SocialMediaItems',
                'Item' => $marshaler->marshalJson(json_encode($this->buildPayload())),
            ]);
        } catch (Exception $e) {
            echo "Unable to add item:".PHP_EOL;
            echo $e->getMessage().PHP_EOL;
            die;
        }
    }

    /**
     * @return SocialMediaItem
     * @throws Exception
     */
    protected function buildPayload()
    {
        $author = $this->item['user']['name'];
        $text = $this->item['text'];
        $tags = $this->getTags();
        $createdAt = $this->formatCreatedAt();

        return new SocialMediaItem(
            $author,
            $text,
            $tags,
            $createdAt
        );
    }

    /**
     * @return false|string
     */
    protected function formatCreatedAt()
    {
        return date('Y-m-d H:i:s', strtotime($this->item['created_at']));
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
