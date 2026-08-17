<?php

namespace App\Listeners;

use App\Events\ListingCreated;
use Illuminate\Support\Facades\Log;

class NotifyAdminNewListing
{
    public function handle(ListingCreated $event): void
    {
        Log::info('New listing created: ' . $event->listing->title);
    }
}
