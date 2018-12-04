<?php

namespace App;

use JsonSerializable;
use App\Contracts\CountableModelInterface;

/**
 * Class Item
 */
class Item implements CountableModelInterface, JsonSerializable
{
    /** @var array */
    protected $payload = [];

    /**
     * Item constructor.
     * @param array $payload
     * @throws \Exception
     */
    public function __construct(array $payload) {
        if (
            !isset($payload['Author'])
            || !isset($payload['PublishedAt'])
            || !isset($payload['Text'])
            || !isset($payload['Tags'])
        ) {
            throw new \Exception('Insufficient data. Cannot create Item.');
        }

        $this->payload = $payload;
    }

    /**
     * @return array
     */
    public function getKey(): array
    {
        return [
            'Author' => $this->payload['Author'],
            'PublishedAt' => $this->payload['PublishedAt'],
        ];
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return 'Items';
    }

    /**
     * @return string
     */
    public function getAuthor():string
    {
        return $this->payload['Author'];
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->payload['Text'];
    }

    /**
     * @return mixed
     */
    public function getPublishedAt()
    {
        return $this->payload['PublishedAt'];
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->payload;
    }
}
