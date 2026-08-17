<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Listing\Listing;
use App\Models\Location\City;
use App\Models\Listing\Category;
use App\Models\Listing\ListingFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $cityId = $request->input('city');
        $categoryId = $request->input('category');
        $sort = $request->input('sort', 'newest');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $minRating = $request->input('min_rating', 0);
        $features = $request->input('features', []);

        $relations = [
            'media',
            'city.translations',
            'country',
            'categories.translations',
            'features.translations',
            'type',
            'business',
        ];

        $listingsQuery = Listing::with($relations)
            ->where('status', 'published')
            ->where('is_hidden', false);

        if (!empty($query)) {
            $listingsQuery->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('short_description', 'LIKE', "%{$query}%");
            });
        }

        if ($cityId) {
            $listingsQuery->where('city_id', $cityId);
        }

        if ($categoryId) {
            $listingsQuery->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        if ($minRating > 0) {
            $listingsQuery->where('average_rating', '>=', $minRating);
        }

        if ($minPrice !== null || $maxPrice !== null) {
            $listingsQuery->whereHas('prices', function ($q) use ($minPrice, $maxPrice) {
                if ($minPrice !== null) {
                    $q->where('price', '>=', $minPrice);
                }
                if ($maxPrice !== null) {
                    $q->where('price', '<=', $maxPrice);
                }
            });
        }

        if (!empty($features)) {
            foreach ($features as $featureId) {
                $listingsQuery->whereHas('features', function ($q) use ($featureId) {
                    $q->where('listing_features.id', $featureId);
                });
            }
        }

        switch ($sort) {
            case 'rating':
                $listingsQuery->orderBy('average_rating', 'desc');
                break;
            case 'oldest':
                $listingsQuery->orderBy('created_at', 'asc');
                break;
            case 'price_low':
                $listingsQuery->orderByRaw('(SELECT MIN(price) FROM listing_prices WHERE listing_prices.listing_id = listings.id) ASC');
                break;
            case 'price_high':
                $listingsQuery->orderByRaw('(SELECT MAX(price) FROM listing_prices WHERE listing_prices.listing_id = listings.id) DESC');
                break;
            case 'newest':
            default:
                $listingsQuery->orderBy('created_at', 'desc');
                break;
        }

        $results = $listingsQuery->paginate(12);

        $favoritedIds = [];
        if (auth()->check()) {
            $favoritedIds = DB::table('favorites')
                ->where('user_id', auth()->id())
                ->whereIn('listing_id', $results->pluck('id'))
                ->pluck('listing_id')
                ->toArray();
        }

        $cities = Cache::remember('search_cities', 3600, function () {
            return City::with('translations')->orderBy('name')->get();
        });

        $categories = Cache::remember('search_categories', 3600, function () {
            return Category::whereNull('parent_id')
                ->with(['children.translations', 'translations'])
                ->orderBy('name')
                ->get();
        });

        $allFeatures = Cache::remember('search_features', 3600, function () {
            return ListingFeature::with('translations')->get();
        });

        return view('public.search', compact(
            'results',
            'favoritedIds',
            'cities',
            'categories',
            'allFeatures',
            'query',
            'cityId',
            'categoryId',
            'sort',
            'minPrice',
            'maxPrice',
            'minRating',
            'features'
        ));
    }
}
