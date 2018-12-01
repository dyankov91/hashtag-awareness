<?php

namespace App\Listeners;

use Exception;
use Aws\Sdk as AwsSdk;
use Aws\DynamoDb\Marshaler;
use App\Events\ItemCreated;
use App\Contracts\CountableModelInterface;

/**
 * Class IncreaseTableCount
 */
class IncreaseTableCount
{
    /** @var AwsSdk */
    protected $aws;

    /** @var Marshaler */
    protected $marshaler;

    /**
     * Create the event listener.
     * @param AwsSdk    $aws
     * @param Marshaler $marshaler
     */
    public function __construct(AwsSdk $aws, Marshaler $marshaler)
    {
        $this->aws = $aws;
        $this->marshaler =  $marshaler;
    }

    /**
     * Handle the event.
     * @param ItemCreated $event
     * @return void
     */
    public function handle(ItemCreated $event)
    {
        $item = $event->getItem();
        if ($item instanceof CountableModelInterface) {
            try {
                $dynamodb = $this->aws->createDynamoDb();
                $dynamodb->updateItem([
                    'TableName' => 'Counters',
                    'Key' => $this->marshaler->marshalJson(json_encode(['CountedTable' => $item->getTable()])),
                    'UpdateExpression' => 'ADD CountItems :ins',
                    'ExpressionAttributeValues' => $this->marshaler->marshalJson(json_encode([':ins' => 1])),
                ]);
            } catch (Exception $e) {
                echo "Unable to increase table items count:".PHP_EOL;
                echo $e->getMessage().PHP_EOL;
                die;
            }
        }
    }
}
