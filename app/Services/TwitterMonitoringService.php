<?php

namespace App\Services;

use ErrorException;
use OauthPhirehose;
use App\Jobs\ProcessTwitterFeedItem;
use App\Contracts\SocialMediaMonitoringInterface;

/**
 * Class MonitoringService
 */
class TwitterMonitoringService extends OauthPhirehose implements SocialMediaMonitoringInterface
{
    /**
     * @param array $trackKeywords
     */
    public function setKeywords(array $trackKeywords): void
    {
        $this->setTrack($trackKeywords);
    }

    /**
     * @param bool $reconnect
     * @throws ErrorException
     */
    public function monitor(bool $reconnect = true): void
    {
        $this->consume($reconnect);
    }

    /**
     * @param string $status
     */
    public function enqueueStatus($status)
    {
        $item = json_decode($status, true);

        dispatch(new ProcessTwitterFeedItem($item));
    }
}
