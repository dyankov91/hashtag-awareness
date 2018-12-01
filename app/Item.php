<?php

namespace App;

use JsonSerializable;
use Ramsey\Uuid\Uuid;
use App\Contracts\CountableModelInterface;

/**
 * Class Item
 */
class Item implements CountableModelInterface, JsonSerializable
{
    /**
     * Item constructor.
     * @param string $driver
     * @param string $publishedAt
     * @param string $author
     * @param string $text
     * @param array  $tags
     * @throws \Exception
     */
    public function __construct(
        string $driver,
        string $publishedAt,
        string $author,
        string $text,
        array $tags
    ) {
        $this->payload = [
            '_id' => Uuid::uuid4()->toString(),
            'Driver' => $driver,
            'PublishedAt' => (int) $publishedAt,
            'Author' => $author,
            'Text' => $text,
            'Tags' => $tags,
        ];
    }

    /** @var array */
    protected $payload = [];

    /**
     * @return string
     */
    public function getTable(): string
    {
        return 'Items';
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->payload;
    }
}
