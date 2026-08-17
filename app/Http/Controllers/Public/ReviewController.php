<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Listing\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Listing $listing)
    {
        $this->authorize('rate', $listing);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        if ($listing->reviews()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'You have already reviewed this listing.');
        }

        $listing->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->body,
            'status' => 'approved',
        ]);

        $listing->updateAverageRating();

        return back()->with('success', 'Review added successfully.');
    }
}