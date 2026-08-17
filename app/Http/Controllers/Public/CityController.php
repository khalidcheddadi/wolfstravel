<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Location\City;
use App\Models\Listing\Listing;
use Illuminate\Support\Facades\Cache;

class CityController extends Controller
{
    public function show(string $slug)
    {
        $city = Cache::remember("city_{$slug}", 3600, function () use ($slug) {
            return City::where('slug', $slug)->firstOrFail();
        });

        $listings = Cache::remember("city_listings_{$slug}", 3600, function () use ($city) {
            return Listing::with(['media', 'city', 'country', 'categories'])
                ->where('status', 'published')
                ->where('is_hidden', false)
                ->where('city_id', $city->id)
                ->latest('published_at')
                ->paginate(12);
        });

        $mainCategories = Cache::remember('main_categories', 86400, function () {
            return \App\Models\Listing\Category::whereNull('parent_id')
                ->has('listings')
                ->withCount(['listings' => function ($query) {
                    $query->where('status', 'published')->where('is_hidden', false);
                }])
                ->get();
        });

        $metaTitle = $city->meta_title ?? $city->name . ' - أفضل الأنشطة في المدينة';
        $metaDescription = $city->meta_description ?? "اكتشف أفضل الأنشطة والأماكن في {$city->name}.";

        return view('public.city.show', compact(
            'city',
            'listings',
            'mainCategories',
            'metaTitle',
            'metaDescription'
        ));
    }
}
