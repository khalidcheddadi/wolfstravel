<?php

namespace App\Listeners;

use App\Events\ListingPublished;
use Illuminate\Support\Facades\Log;

class NotifyOwnerListingPublished
{
    public function handle(ListingPublished $event): void
    {
        Log::info('Owner notified: ' . $event->listing->business->owner->email);
    }
}
