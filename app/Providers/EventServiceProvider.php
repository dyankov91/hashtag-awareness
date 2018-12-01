<?php

namespace App\Providers;

use App\Events\ItemCreated;
use App\Events\ItemDeleted;
use App\Listeners\DecreaseTableCount;
use App\Listeners\IncreaseTableCount;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ItemCreated::class => [
            IncreaseTableCount::class,
        ],
        ItemDeleted::class => [
            DecreaseTableCount::class,
        ],
    ];
}
