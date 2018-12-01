<?php

namespace App\Listeners;

use Exception;
use Aws\Sdk as AwsSdk;
use App\Events\ItemDeleted;
use Aws\DynamoDb\Marshaler;
use App\Contracts\CountableModelInterface;

/**
 * Class DecreaseTableCount
 */
class DecreaseTableCount
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
        $this->marshaler = $marshaler;
    }

    /**
     * Handle the event.
     * @param ItemDeleted $event
     * @return void
     */
    public function handle(ItemDeleted $event)
    {
        $item = $event->getItem();
        if ($item instanceof CountableModelInterface) {
            try {
                $dynamodb = $this->aws->createDynamoDb();
                $dynamodb->updateItem([
                    'TableName' => 'Counters',
                    'Key' => $this->marshaler->marshalJson(json_encode(['CountedTable' => $item->getTable()])),
                    'UpdateExpression' => 'REMOVE CountItems :n',
                    'ExpressionAttributeValues' => $this->marshaler->marshalJson(json_encode([':n' => 1])),
                ]);
            } catch (Exception $e) {
                echo "Unable to decrease table `{$item->getTable()}` items count:".PHP_EOL;
                echo $e->getMessage().PHP_EOL;
                die;
            }
        }
    }
}
