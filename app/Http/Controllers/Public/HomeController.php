<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Listing\Category;
use App\Models\Listing\Listing;
use App\Models\Listing\ListingFeature;
use App\Models\Location\City;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'city' => $request->input('city'),
            'category' => $request->input('category'),
            'sort' => $request->input('sort', 'newest'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'min_rating' => (int) $request->input('min_rating', 0),
            'features' => $request->input('features', []),
        ];

        $query = Listing::with($this->listingRelations())
            ->where('status', 'published')
            ->where('is_hidden', false);

        $this->applyFilters($query, $filters);
        $this->applySorting($query, $filters['sort']);

        $listings = $query->paginate(12);
        $favoritedIds = $this->getFavoritedIds($listings);

        return view('public.home', [
            'listings' => $listings,
            'favoritedIds' => $favoritedIds,
            'featuredListings' => $this->getFeaturedListings(),
            'cities' => $this->getCities(),
            'categories' => $this->getCategories(),
            'allFeatures' => $this->getFeatures(),
            'cityId' => $filters['city'],
            'categoryId' => $filters['category'],
            'sort' => $filters['sort'],
            'minPrice' => $filters['min_price'],
            'maxPrice' => $filters['max_price'],
            'minRating' => $filters['min_rating'],
            'features' => $filters['features'],
            'posts' => $this->getPosts(),
        ]);
    }

    private function listingRelations(): array
    {
        return [
            'media',
            'city.translations',
            'country',
            'categories.translations',
            'features.translations',
            'type',
            'business',
        ];
    }

    private function applyFilters($query, array $filters): void
    {
        if ($filters['city']) {
            $query->where('city_id', $filters['city']);
        }

        if ($filters['category']) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('category_id', $filters['category']);
            });
        }

        if ($filters['min_rating'] > 0) {
            $query->where('average_rating', '>=', $filters['min_rating']);
        }

        if ($filters['min_price'] !== null || $filters['max_price'] !== null) {
            $query->whereHas('prices', function ($q) use ($filters) {
                if ($filters['min_price'] !== null) {
                    $q->where('price', '>=', $filters['min_price']);
                }

                if ($filters['max_price'] !== null) {
                    $q->where('price', '<=', $filters['max_price']);
                }
            });
        }

        $features = $filters['features'];

        if (! empty($features)) {
            $query->whereHas('features', function ($q) use ($features) {
                $q->whereIn('listing_features.id', $features);
            }, '=', count($features));
        }
    }

    private function applySorting($query, string $sort): void
    {
        switch ($sort) {
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_low':
                $query->orderByRaw('(SELECT MIN(price) FROM listing_prices WHERE listing_prices.listing_id = listings.id) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('(SELECT MAX(price) FROM listing_prices WHERE listing_prices.listing_id = listings.id) DESC');
                break;
            default:
                $query->orderBy('published_at', 'desc');
                break;
        }
    }

    private function getFavoritedIds($listings): array
    {
        if (! auth()->check()) {
            return [];
        }

        return DB::table('favorites')
            ->where('user_id', auth()->id())
            ->whereIn('listing_id', $listings->pluck('id'))
            ->pluck('listing_id')
            ->toArray();
    }

    private function getCities()
    {
        return Cache::remember('home_cities', 3600, function () {
            return City::with('translations')->orderBy('name')->get();
        });
    }

    private function getCategories()
    {
        return Cache::remember('home_categories', 3600, function () {
            return Category::whereNull('parent_id')
                ->with(['children.translations', 'translations'])
                ->withCount(['listings' => function ($q) {
                    $q->where('status', 'published');
                }])
                ->orderBy('name')
                ->get();
        });
    }

    private function getFeatures()
    {
        return Cache::remember('home_features', 3600, function () {
            return ListingFeature::with('translations')->get();
        });
    }

    private function getFeaturedListings()
    {
        return Cache::remember('home_featured_listings', 3600, function () {
            return Listing::with([
                'media',
                'city.translations',
                'country',
                'type',
                'features.translations',
                'business',
            ])
                ->where('status', 'published')
                ->where('is_hidden', false)
                ->where('views', '>', 100)
                ->orderBy('views', 'desc')
                ->take(6)
                ->get();
        });
    }

    private function getPosts()
    {
        return Post::visible()
            ->with(['category', 'user'])
            ->latest('published_at')
            ->take(4)
            ->get();
    }
}
