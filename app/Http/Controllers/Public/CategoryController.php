<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Listing\Category;
use App\Models\Listing\Listing;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Cache::remember("category_{$slug}", 3600, function () use ($slug) {
            return Category::with(['children', 'parent'])
                ->where('slug', $slug)
                ->firstOrFail();
        });

        $listings = Listing::with(['media', 'city', 'country', 'categories'])
            ->where('status', 'published')
            ->where('is_hidden', false)
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('category_id', $category->id)
                    ->orWhereIn('category_id', $category->children->pluck('id'));
            })
            ->latest('published_at')
            ->paginate(12);

            return Category::whereNull('parent_id')
                ->has('listings')
                ->withCount(['listings' => function ($query) {
                    $query->where('status', 'published');
                }])
                ->get();

        $metaTitle = $category->meta_title ?? $category->name . ' - Discover tourism in Spain and Europe';
        $metaDescription = $category->meta_description ?? "Explore the best tourist activities in the {$category->name} category in Spain and Europe.";

        return view('public.category.show', compact(
            'category',
            'listings',
            'mainCategories',
            'metaTitle',
            'metaDescription'
        ));
    }
}