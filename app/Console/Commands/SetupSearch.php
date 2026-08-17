<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Meilisearch\Client;

class SetupSearch extends Command
{
    protected $signature = 'search:setup';
    protected $description = 'Configure Meilisearch settings';

    public function handle()
    {
        $client = new Client(
            config('services.meilisearch.host'),
            config('services.meilisearch.key')
        );

        $index = $client->index('listings_index');

        $index->updateFilterableAttributes([
            'city_id',
            'category_ids',
            'categories',
            'features',
            'average_rating',
            'status',
            'price_min',
            '_geo'
        ]);

        $index->updateSortableAttributes([
            'average_rating',
            'created_at',
            'price_min'
        ]);

        $index->updateSearchableAttributes([
            'title',
            'description',
            'short_description',
            'address',
            'city',
            'country',
            'categories',
            'title_es',
            'title_fr',
            'title_ar',
            'title_de',
            'description_es',
            'description_fr',
            'description_ar',
            'description_de',
            'short_description_es',
            'short_description_fr',
            'short_description_ar',
            'short_description_de',
        ]);

        $this->info('✅ Meilisearch configuration applied.');
    }
}
