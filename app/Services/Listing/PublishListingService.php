<?php

namespace App\Services\Listing;

use App\Models\Listing\Listing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublishListingService
{
    public function execute(Listing $listing): Listing
    {
        return DB::transaction(function () use ($listing) {

            $listing->update([
                'status' => 'published',
                'published_at' => now(),
            ]);

           
            event(new \App\Events\ListingPublished($listing));

            Log::info('Listing published: ' . $listing->title);

            return $listing;
        });
    }
}
