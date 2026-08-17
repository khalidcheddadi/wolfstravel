<?php

namespace App\Events;

use App\Models\Listing\Listing;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ListingUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Listing $listing)
    {
    }
}
