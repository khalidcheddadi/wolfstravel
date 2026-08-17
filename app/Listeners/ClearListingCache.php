<?php

namespace App\Listeners;

use App\Events\ListingUpdated;
use App\Services\CacheService;

class ClearListingCache
{
    public function handle(ListingUpdated $event): void
    {
        CacheService::clearListingCache($event->listing->id);
    }
}
