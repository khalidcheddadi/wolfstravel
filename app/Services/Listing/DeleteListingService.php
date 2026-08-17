<?php

namespace App\Services\Listing;

use App\Models\Listing\Listing;
use Illuminate\Support\Facades\DB;

class DeleteListingService
{
    public function execute(Listing $listing): bool
    {
        return DB::transaction(function () use ($listing) {

            $listing->clearMediaCollection('images');

            
            return $listing->delete();
        });
    }
}
