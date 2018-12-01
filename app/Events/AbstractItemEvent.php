<?php

namespace App\Events;

use App\Item;

/**
 * Class ItemEvent
 */
abstract class AbstractItemEvent extends Event
{
    /** @var Item */
    protected $item;

    /**
     * Create a new event instance.
     * @param Item $item
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }
}
