<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Listing\Listing;
use App\Models\Location\City;
use App\Models\Listing\Category;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml';

    public function handle()
    {
        $sitemap = Sitemap::create();
        $sitemap->add(Url::create('/')->setPriority(1.0));

        Listing::where('status', 'published')->chunk(100, function ($listings) use ($sitemap) {
            foreach ($listings as $listing) {
                $sitemap->add(Url::create(route('listing.show', $listing->slug))
                    ->setLastModificationDate($listing->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8));
            }
        });

        City::chunk(100, function ($cities) use ($sitemap) {
            foreach ($cities as $city) {
                $sitemap->add(Url::create(route('city.show', $city->slug))->setPriority(0.6));
            }
        });

        Category::chunk(100, function ($categories) use ($sitemap) {
            foreach ($categories as $category) {
                $sitemap->add(Url::create(route('category.show', $category->slug))->setPriority(0.6));
            }
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));
        $this->info(' Sitemap generated.');
    }
}
