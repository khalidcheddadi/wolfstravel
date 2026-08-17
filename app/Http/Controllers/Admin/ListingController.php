<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing\Listing;
use App\Models\Review\Review;
use App\Services\Listing\PublishListingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ListingController extends Controller
{
    public function __construct(
        private PublishListingService $publishService
    ) {}

    public function index()
    {
        $listings = Listing::with(['business', 'categories', 'media', 'city', 'country'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.listings.index', compact('listings'));
    }

    public function edit(Listing $listing)
    {
        Gate::authorize('update', $listing);

        return view('admin.listings.edit', [
            'listing' => $listing,
            'listingTypes' => \App\Models\Listing\ListingType::all(),
            'countries' => \App\Models\Location\Country::orderBy('name')->get(),
            'cities' => \App\Models\Location\City::orderBy('name')->get(),
            'categories' => \App\Models\Listing\Category::whereNull('parent_id')->with('children')->get(),
            'features' => \App\Models\Listing\ListingFeature::all(),
            'tags' => \App\Models\Listing\ListingTag::all(),
        ]);
    }

    public function update(Request $request, Listing $listing)
    {
        Gate::authorize('update', $listing);

        $request->validate([
            'listing_type_id' => 'required|exists:listing_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_ids' => 'required|array|min:1',
            'city_id' => 'required|exists:cities,id',
            'country_id' => 'required|exists:countries,id',
            'features' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer',
            'is_hidden' => 'nullable|boolean',
            'hidden_reason' => 'nullable|string|max:500',
            'moderation_comment' => 'nullable|string|max:1000',
        ]);

        $listingData = [
            'listing_type_id' => $request->listing_type_id,
            'title' => $request->title,
            'short_description' => $request->short_description ?? null,
            'description' => $request->description,
            'city_id' => $request->city_id,
            'country_id' => $request->country_id,
            'address' => $request->address ?? null,
            'latitude' => $request->latitude ?? null,
            'longitude' => $request->longitude ?? null,
            'category_ids' => $request->category_ids,
            'features' => $request->input('features', []),
            'images' => $request->file('images', []),
            'remove_images' => $request->input('remove_images', []),
        ];

        $isHidden = $request->boolean('is_hidden');

        $listing->update([
            'is_hidden' => $isHidden,
            'hidden_reason' => $isHidden ? ($request->hidden_reason ?? 'Hidden by admin.') : null,
            'moderation_comment' => $request->input('moderation_comment') ?: ($isHidden ? ($request->hidden_reason ?? 'Hidden by admin.') : null),
        ]);

        $this->publishService->execute($listing);

        $service = new \App\Services\Listing\UpdateListingService();
        $service->execute($listing, $listingData);

        return redirect()->route('admin.listings.index')->with('success', 'Listing updated successfully.');
    }

    public function review(Listing $listing)
    {
        Gate::authorize('publish', $listing);

        return view('admin.listings.review', compact('listing'));
    }

    public function approve(Listing $listing)
    {
        Gate::authorize('publish', $listing);

        $this->publishService->execute($listing);

        return redirect()
            ->route('admin.listings.index')
            ->with('success', 'Listing published successfully.');
    }

    public function reject(Request $request, Listing $listing)
    {
        Gate::authorize('publish', $listing);

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $listing->update([
            'status' => 'rejected',
        ]);

        return redirect()
            ->route('admin.listings.index')
            ->with('success', 'Listing rejected.');
    }

    public function rateForm(Listing $listing)
    {
        Gate::authorize('rate', $listing);

        return view('admin.listings.rate', compact('listing'));
    }

    public function rateStore(Request $request, Listing $listing)
    {
        Gate::authorize('rate', $listing);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        if ($listing->reviews()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You have already rated this listing.');
        }

        $listing->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->body,
            'status' => 'approved',
        ]);

        $listing->updateAverageRating();

        return redirect()
            ->route('admin.listings.index')
            ->with('success', 'Rating added successfully.');
    }
}