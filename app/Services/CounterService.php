<?php

namespace App\Services;

use Aws\Sdk;
use Aws\DynamoDb\Marshaler;

/**
 * Class CounterService
 */
class CounterService
{
    /** @var Sdk */
    protected $aws;

    /** @var Marshaler */
    protected $marshaler;

    /**
     * CounterService constructor.
     * @param Sdk       $aws
     * @param Marshaler $marshaler
     */
    public function __construct(Sdk $aws, Marshaler $marshaler)
    {
        $this->aws = $aws;
        $this->marshaler = $marshaler;
    }

    /**
     * @param string $countableTable
     * @return int
     */
    public function getCountFor(string $countableTable): int
    {
        $dynamoDB = $this->aws->createDynamoDb();
        $result = $dynamoDB->getItem([
            "ConsistentRead" => true,
            'TableName' => 'Counters',
            'Key' => $this->marshaler->marshalJson(json_encode(['CountedTable' => $countableTable])),
            'AttributesToGet' => ['CountItems'],
        ]);

        return $result->search('Item.CountItems') ?
                $this->marshaler->unmarshalValue($result->search('Item.CountItems'))
                : 0;
    }
}
