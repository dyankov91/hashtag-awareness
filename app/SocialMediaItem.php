<?php

namespace App;

use JsonSerializable;
use Ramsey\Uuid\Uuid;

/**
 * Class SocialMediaItem
 */
class SocialMediaItem implements JsonSerializable
{
    /** @var array */
    protected $payload = [];

    /**
     * SocialMediaItem constructor.
     * @param string $author
     * @param string $text
     * @param array  $tags
     * @param string $createdAt
     * @throws \Exception
     */
    public function __construct(string $author, string $text, array $tags, string $createdAt)
    {
        $this->payload = [
            'Id' => Uuid::uuid4(),
            'Author' => $author,
            'Text' => $text,
            'Tags' => $tags,
            'CreatedAt' => $createdAt,
        ];
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->payload;
    }
}
