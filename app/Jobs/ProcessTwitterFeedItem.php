<?php

namespace App\Jobs;

use App\SocialMediaItem;

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
     */
    public function handle()
    {
        $author = $this->item['user']['name'];
        $text = $this->item['text'];
        $createdAt = date('Y-m-d H:i:s', strtotime($this->item['created_at']));
        $tags = [];
        foreach ($this->item['entities']['hashtags'] as $tag) {
            $tags[] = $tag['text'];
        }

        $payload = new SocialMediaItem($author, $text, $tags, $createdAt);

        // @TODO store the payload in DynamoDb
    }
}
