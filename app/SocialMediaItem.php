<?php

namespace App;

use JsonSerializable;

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
     */
    public function __construct(string $author, string $text, array $tags, string $createdAt)
    {
        $this->payload = [
            'author' => $author,
            'text' => $text,
            'tags' => $tags,
            'created_at' => $createdAt,
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
