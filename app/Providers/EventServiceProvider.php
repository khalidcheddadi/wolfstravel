<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [

        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        \App\Events\ListingCreated::class => [
            \App\Listeners\NotifyAdminNewListing::class,
            \App\Listeners\TranslateNewListing::class,
        ],

        \App\Events\ListingUpdated::class => [
            \App\Listeners\ClearListingCache::class,
        ],

        \App\Events\ListingPublished::class => [
            \App\Listeners\NotifyOwnerListingPublished::class,
        ],
    ];

    /**
     */
    public function boot(): void
    {
        //
    }

    /**
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
