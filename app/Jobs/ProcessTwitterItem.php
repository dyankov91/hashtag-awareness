<?php

namespace App\Jobs;

use App\Item;
use Exception;
use App\Services\ItemService;

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
     * @param ItemService $itemService
     */
    public function handle(ItemService $itemService)
    {
        $item = $this->makeItem();
        $itemService->storeItem($item);
    }

    /**
     * @return Item
     */
    protected function makeItem()
    {
        try {
            return new Item([
                'Driver' => 'Twitter',
                'Author' => $this->item['user']['name'],
                'PublishedAt' => strtotime($this->item['created_at']),
                'Text' => $this->item['text'],
                'Tags' => $this->getTags(),
            ]);
        } catch (Exception $e) {
            echo "Unable to make item:".PHP_EOL;
            echo $e->getMessage().PHP_EOL;
            die;
        }
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
