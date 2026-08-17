<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     */
    private const CACHE_KEYS = [
        'cities' => 'cities_all',
        'categories' => 'categories_main',
        'categories_with_children' => 'categories_with_children',
        'features' => 'features_all',
        'featured_listings' => 'listings_featured',
        'listings_count' => 'listings_count_total',
        'listings_public' => 'listings_public_layout',
    ];

    private const CACHE_TTL = [
        'cities' => 86400,
        'categories' => 86400,
        'features' => 43200,
        'listings' => 3600,
        'dynamic' => 300,
    ];

    /**
     */
    public static function getCities()
    {
        return Cache::remember(
            self::CACHE_KEYS['cities'],
            self::CACHE_TTL['cities'],
            function () {
                return \App\Models\Location\City::with('translations')
                    ->orderBy('name')
                    ->get();
            }
        );
    }

    /**
     */
    public static function getMainCategories()
    {
        return Cache::remember(
            self::CACHE_KEYS['categories'],
            self::CACHE_TTL['categories'],
            function () {
                return \App\Models\Listing\Category::whereNull('parent_id')
                    ->with(['children.translations', 'translations'])
                    ->withCount(['listings' => function ($q) {
                        $q->where('status', 'published');
                    }])
                    ->orderBy('name')
                    ->get();
            }
        );
    }

    /**
     */
    public static function getAllFeatures()
    {
        return Cache::remember(
            self::CACHE_KEYS['features'],
            self::CACHE_TTL['features'],
            function () {
                return \App\Models\Listing\ListingFeature::with('translations')->get();
            }
        );
    }

    /**
     */
    public static function getFeaturedListings()
    {
        return Cache::remember(
            self::CACHE_KEYS['featured_listings'],
            self::CACHE_TTL['listings'],
            function () {
                return \App\Models\Listing\Listing::with([
                    'media',
                    'city.translations',
                    'country',
                    'type',
                    'features.translations',
                    'business'
                ])
                    ->where('status', 'published')
                    ->where('views', '>', 100)
                    ->orderBy('views', 'desc')
                    ->take(6)
                    ->get();
            }
        );
    }

    /**
     */
    public static function clearListingCache($listingId = null)
    {
        $keys = [
            "listing_{$listingId}",
            "related_{$listingId}",
            self::CACHE_KEYS['featured_listings'],
            self::CACHE_KEYS['listings_count'],
            self::CACHE_KEYS['listings_public'],
        ];

        foreach ($keys as $key) {
            if (!empty($key)) {
                Cache::forget($key);
            }
        }

        self::clearSearchCache();
    }

    /**
     */
    public static function clearSearchCache()
    {
        Cache::forget('search_cities');
        Cache::forget('search_categories');
        Cache::forget('search_features');
        Cache::forget('home_cities');
        Cache::forget('home_categories');
        Cache::forget('home_features');
        Cache::forget('all_cities');
        Cache::forget('all_categories_with_children');
        Cache::forget('all_features');
    }

    /**
     */
    public static function clearCategoryCache($categoryId = null)
    {
        Cache::forget(self::CACHE_KEYS['categories']);
        Cache::forget(self::CACHE_KEYS['categories_with_children']);

        if ($categoryId) {
            Cache::forget("category_{$categoryId}");
        }

        self::clearSearchCache();
    }

    /**
     */
    public static function flushAll()
    {
        Cache::flush();
    }
}
