<?php

namespace App\Jobs;

use Exception;
use App\Contracts\SocialMediaMonitoringInterface;

/**
 * Class MonitorSocialMedia
 */
class MonitorSocialMedia
{
    /** @var SocialMediaMonitoringInterface */
    protected $monitoringService;

    /** @var array */
    protected $keywords;

    /**
     * MonitorSocialMedia constructor.
     * @param SocialMediaMonitoringInterface $monitoringService
     * @param array                          $keywords
     */
    public function __construct(SocialMediaMonitoringInterface $monitoringService, array $keywords)
    {
        $this->monitoringService = $monitoringService;
        $this->keywords = $keywords;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->monitoringService->setKeywords($this->keywords);
            $this->monitoringService->monitor();
        } catch (Exception $e) {
            echo $e->getFile().':'.$e->getLine().PHP_EOL.$e->getMessage();
            die;
        }
    }
}
