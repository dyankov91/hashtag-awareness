<?php

namespace App\Jobs;

use App\Item;
use App\Services\ItemService;

/**
 * Class DetectAnomalies
 */
class DetectAnomalies
{
    /** @var string */
    protected $keyword;

    /**
     * DetectAnomalies constructor.
     * @param string $keyword
     */
    public function __construct(string $keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * @param ItemService $itemService
     */
    public function handle(ItemService $itemService)
    {
        $percentageError = 10;
        $numSets = 10;
        $itemsPerTestSet = 100;
        $controlGroup = $this->test($itemService, $itemsPerTestSet, null);

        try {
            $testSetResults = [];
            $nextExclusiveStartKey = $controlGroup['nextExclusiveStartKey'];
            do {
                $testSet = $this->test($itemService, $itemsPerTestSet, $nextExclusiveStartKey);
                $testSetResults[] = $testSet['result'];
                $nextExclusiveStartKey = $testSet['nextExclusiveStartKey'];
            } while (count($testSetResults) < $numSets && $nextExclusiveStartKey);
            
            if (count($testSetResults) < $numSets) {
                echo "[DetectAnomalies] Not enough data to finish all {$numSets} required test sets!".PHP_EOL;
                die;
            }

            $totalMentions = array_sum(array_values($testSetResults));
            $avgPerSet = $totalMentions/$numSets;
            $minWithError = $avgPerSet - ($avgPerSet * $percentageError / 100);
            $maxWithError = $avgPerSet + ($avgPerSet * $percentageError / 100);
            $status = ($controlGroup['result'] >= $minWithError && $controlGroup['result'] <= $maxWithError) ? 'OK' : 'FAIL';

            // @TODO Evaluate every set and store results.
            echo "[DetectAnomalies] Count Sets: {$numSets}; Items Per Set {$itemsPerTestSet};".PHP_EOL;
            echo "[DetectAnomalies] Range: ({$minWithError} - {$maxWithError}); Control Group: {$controlGroup['result']};".PHP_EOL;
            echo "[DetectAnomalies] Control Group Status: {$status};".PHP_EOL;
        } catch (\Exception $e) {
            echo '[DetectAnomalies] Job failed!'.PHP_EOL;
            echo '[DetectAnomalies]'.$e->getMessage().':'.$e->getLine().PHP_EOL;
        }
    }

    // @TODO move to separated service
    protected function test($itemService, int $testSetItems, ?array $exclusiveStartKey)
    {
        $response = [
            'result' => 0,
            'nextExclusiveStartKey' => null,
        ];

        $testSet = $itemService->getItems($testSetItems, false, $exclusiveStartKey);
        /** @var Item $item */
        foreach ($testSet['items'] as $item) {
            $response['result'] += preg_match_all('/\b'.$this->keyword.'\b/i', $item->getText());
        }
        $response['nextExclusiveStartKey'] = $testSet['@metadata']['nextExclusiveStartKey'];

        return $response;
    }
}
