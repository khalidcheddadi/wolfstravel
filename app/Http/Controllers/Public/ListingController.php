<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Listing\Category;
use App\Models\Listing\Listing;
use App\Models\Listing\ListingFeature;
use App\Models\Location\City;
use Illuminate\Support\Facades\Cache;

class ListingController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        return (new SearchController())->index($request);
    }

    public function show(string $slug)
    {
        $listing = Cache::remember("listing_{$slug}", 3600, function () use ($slug) {
            return Listing::with([
                'business',
                'type',
                'city',
                'country',
                'categories',
                'features',
                'tags',
                'media',
                'reviews' => function ($query) {
                    $query->where('status', 'approved')->latest();
                },
                'reviews.user',
            ])
                ->where('slug', $slug)
                ->where('status', 'published')
                ->where('is_hidden', false)
                ->firstOrFail();
        });

        $listing->increment('views');

        $relatedListings = Cache::remember("related_{$listing->id}", 3600, function () use ($listing) {
            return Listing::with(['media', 'city'])
                ->where('status', 'published')
                ->where('is_hidden', false)
                ->where('id', '!=', $listing->id)
                ->where(function ($query) use ($listing) {
                    $query->where('city_id', $listing->city_id)
                        ->orWhereHas('categories', function ($categoryQuery) use ($listing) {
                            $categoryQuery->whereIn('category_id', $listing->categories->pluck('id'));
                        });
                })
                ->take(6)
                ->get();
        });

        $cities = Cache::remember('all_cities', 3600, function () {
            return City::orderBy('name')->get();
        });

        $categories = Cache::remember('all_categories_with_children', 3600, function () {
            return Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        });

        $allFeatures = Cache::remember('all_features', 3600, function () {
            return ListingFeature::orderBy('name')->get();
        });

        $listingsCount = Cache::remember('total_listings_count', 3600, function () {
            return Listing::where('status', 'published')->where('is_hidden', false)->count();
        });

        $listings = Cache::remember('public_listings_for_layout', 3600, function () {
            return Listing::with(['media', 'city'])
                ->where('status', 'published')
                ->where('is_hidden', false)
                ->latest()
                ->take(6)
                ->get();
        });

        return view('public.listings.show', compact(
            'listing',
            'relatedListings',
            'cities',
            'categories',
            'allFeatures',
            'listings',
            'listingsCount'
        ));
    }
}
