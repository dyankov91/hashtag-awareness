<?php

namespace App\Contracts;

use ErrorException;

/**
 * Interface SocialMediaMonitoringInterface
 */
interface SocialMediaMonitoringInterface
{
    /**
     * Sets the tracking keywords for which to monitor
     * @param array $trackKeywords
     * @return void
     */
    public function setKeywords(array $trackKeywords): void;

    /**
     * Starts the monitoring process
     * @param bool $reconnect
     * @throws ErrorException
     */
    public function monitor(bool $reconnect = true): void;

    /**
     * Enqueues the payload for every social post to the processing queue
     * @param string $status
     */
    public function enqueueStatus($status);
}
