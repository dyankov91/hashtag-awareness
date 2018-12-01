<?php

namespace App\Jobs;

use Illuminate\Log\Logger;
use App\Services\ItemService;
use App\Services\CounterService;
use App\Services\ArchiverService;

/**
 * Class Archive
 */
class Archive
{
    /**
     * @param CounterService  $counterService
     * @param ItemService     $itemService
     * @param ArchiverService $archiverService
     * @param Logger          $logger
     */
    public function handle(
        CounterService $counterService,
        ItemService $itemService,
        ArchiverService $archiverService,
        Logger $logger
    ) {
        $logger->info("[Archiver] Archiving job starting at: ".gmdate('Y-m-d H:i:s'));

        $totalCountItems = $counterService->getCountFor('Items');
        $limit = env('ITEMS_LIMIT', 100000);
        $toArchive = $totalCountItems - $limit;

        if ($toArchive <= 0) {
            $logger->info("[Archiver] Nothing to archive. Exiting...");

            return;
        }

        try {
            $logger->info("[Archiver] Trying to archiving {$toArchive} items...");

            $itemsToArchive = $itemService->getLastItems($toArchive);
            $archiverService->bulkArchive($itemsToArchive);
            $itemService->bulkDelete($itemsToArchive);

            $logger->info("[Archiver] {$toArchive} items archived at: ".gmdate('Y-m-d H:i:s').PHP_EOL);
        } catch (\Exception $e) {
            $logger->info("[Archiver] Archiver failed to archive {$toArchive} items at: ".gmdate('Y-m-d H:i:s').PHP_EOL);
        }
    }
}
