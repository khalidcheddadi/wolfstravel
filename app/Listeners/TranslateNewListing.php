<?php

namespace App\Listeners;

use App\Events\ListingCreated;
use App\Services\TranslationService;

class TranslateNewListing
{
    protected TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function handle(ListingCreated $event): void
    {
        $listing = $event->listing;
        $locales = ['es', 'fr', 'ar', 'de'];

        foreach ($locales as $locale) {
            $this->translationService->translateModel($listing, $locale, 'en');
        }
    }
}
