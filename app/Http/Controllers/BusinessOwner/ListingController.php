<?php

namespace App\Http\Controllers\BusinessOwner;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessOwner\StoreListingRequest;
use App\Http\Requests\BusinessOwner\UpdateListingRequest;
use App\Models\Listing\Category;
use App\Models\Listing\Listing;
use App\Models\Listing\ListingType;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Services\Listing\CreateListingService;
use App\Services\Listing\UpdateListingService;
use App\Services\Listing\DeleteListingService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function __construct(
        private CreateListingService $createService,
        private UpdateListingService $updateService,
        private DeleteListingService $deleteService
    ) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $business = $user->businesses()->first();

        if (!$business) {
            return redirect()
                ->route('business-owner.business.create')
                ->with('error', 'You must create a business first before adding a listing.');
        }

        $query = $business->listings()
            ->with(['media', 'categories', 'type', 'city', 'country']);

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('short_description', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $listings = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->only(['search', 'status']));

        return view('business-owner.listings.index', compact('listings'));
    }

    public function create()
    {
        $user = auth()->user();
        $business = $user->businesses()->first();

        if (!$business) {
            return redirect()
                ->route('business-owner.business.create')
                ->with('error', 'You must create a business first before adding a listing.');
        }

        return view('business-owner.listings.create', [
            'business' => $business,
            'listingTypes' => ListingType::all(),
            'countries' => Country::orderBy('name')->get(),
            'cities' => City::orderBy('name')->get(),
            'categories' => Category::whereNull('parent_id')->with('children')->get(),
            'features' => \App\Models\Listing\ListingFeature::all(),
        ]);
    }

    public function store(StoreListingRequest $request)
    {
        $user = auth()->user();
        $business = $user->businesses()->first();

        if (!$business) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'No business is associated with your account.'], 422);
            }
            return back()->with('error', 'No business is associated with your account.');
        }

        Gate::authorize('create', Listing::class);

        $data = $request->validated();
        $data['features'] = $this->parseFeaturesInput($request);

        if ($request->has('category_ids_json')) {
            $categoryIds = json_decode($request->input('category_ids_json'), true);
            $data['category_ids'] = is_array($categoryIds) ? $categoryIds : [];
        }

        if ($request->hasFile('images')) {
            $data['images'] = $request->file('images');
            Log::info('Images received for upload', ['count' => count($request->file('images'))]);
        } else {
            $data['images'] = [];
        }

        try {
            $listing = $this->createService->execute($business, $data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Listing created successfully. Awaiting review.',
                    'listing_id' => $listing->id,
                ]);
            }

            return redirect()
                ->route('business-owner.listings.index')
                ->with('success', 'Listing created successfully. Awaiting review.');
        } catch (\Exception $e) {
            Log::error('Failed to create listing: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while saving the listing: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'An error occurred while saving the listing.');
        }
    }

    public function edit(Listing $listing)
    {
        Gate::authorize('update', $listing);

        return view('business-owner.listings.edit', [
            'listing' => $listing,
            'listingTypes' => ListingType::all(),
            'countries' => Country::orderBy('name')->get(),
            'cities' => City::orderBy('name')->get(),
            'categories' => Category::whereNull('parent_id')->with('children')->get(),
            'features' => \App\Models\Listing\ListingFeature::all(),
            'tags' => \App\Models\Listing\ListingTag::all(),
        ]);
    }

    public function update(UpdateListingRequest $request, Listing $listing)
    {
        Gate::authorize('update', $listing);

        $data = $request->validated();
        $data['features'] = $this->parseFeaturesInput($request);
        $data['is_hidden'] = $request->boolean('is_hidden');
        $data['hidden_reason'] = $request->boolean('is_hidden') ? ($request->input('hidden_reason') ?? 'Hidden by admin.') : null;
        $data['moderation_comment'] = $request->input('moderation_comment') ?: ($request->boolean('is_hidden') ? ($request->input('hidden_reason') ?? 'Hidden by admin.') : null);

        if ($request->hasFile('images')) {
            $data['images'] = $request->file('images');
        } else {
            $data['images'] = [];
        }

        try {
            $updatedListing = $this->updateService->execute($listing, $data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Listing updated successfully.',
                    'listing_id' => $updatedListing->id,
                ]);
            }

            return redirect()
                ->route('business-owner.listings.index')
                ->with('success', 'Listing updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update listing: ' . $e->getMessage(), [
                'listing_id' => $listing->id,
                'data' => Arr::except($data, ['images']),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the listing: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'An error occurred while updating the listing.');
        }
    }

    private function parseFeaturesInput($request): array
    {
        if (!$request->has('features')) {
            return [];
        }

        $featuresInput = $request->input('features');

        if (is_array($featuresInput)) {
            if (count($featuresInput) === 1 && is_string($featuresInput[0])) {
                $decoded = json_decode($featuresInput[0], true);
                return is_array($decoded) ? array_values($decoded) : array_values($featuresInput);
            }

            return array_values($featuresInput);
        }

        if (is_string($featuresInput)) {
            $decoded = json_decode($featuresInput, true);
            return is_array($decoded) ? array_values($decoded) : [];
        }

        return [];
    }

    public function destroy(Listing $listing)
    {
        Gate::authorize('delete', $listing);

        $this->deleteService->execute($listing);

        return redirect()
            ->route('business-owner.listings.index')
            ->with('success', 'Listing deleted successfully.');
    }

    public function submit(Listing $listing)
    {
        if ($listing->business->owner_id !== auth()->id()) {
            abort(403);
        }

        $listing->update(['status' => 'submitted']);

        return back()->with('success', 'Listing submitted for review. You will be notified upon approval.');
    }
}