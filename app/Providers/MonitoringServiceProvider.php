<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TwitterMonitoringService;

/**
 * Class MonitoringServiceProvider
 */
class MonitoringServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Twitter Monitoring Service
        $this->app->singleton(TwitterMonitoringService::class, function() {
            $twitterMonitoring = new TwitterMonitoringService(
                env('TWITTER_OAUTH_TOKEN'),
                env('TWITTER_OAUTH_SECRET'),
                TwitterMonitoringService::METHOD_FILTER
            );
            $twitterMonitoring->consumerKey = env('TWITTER_CONSUMER_KEY');
            $twitterMonitoring->consumerSecret = env('TWITTER_CONSUMER_SECRET');

            return $twitterMonitoring;
        });

        // Facebook Monitoring Service (not implemented)
        // $this->app->singleton(FacebookMonitoringService::class, function() {
        //    return new FacebookMonitoringService();
        // });

        // the artisan command is using those aliases to resolve the proper drivers.
        $this->app->alias(TwitterMonitoringService::class, 'twitter');
        // $this->app->alias(FacebookMonitoringService::class, 'facebook');
    }
}
