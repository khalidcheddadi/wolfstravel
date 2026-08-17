<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Listing\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Listing $listing)
    {
        $user = Auth::user();
        $isFavorited = $user->favorites()->where('listing_id', $listing->id)->exists();

        if ($isFavorited) {
            $user->favorites()->detach($listing->id);
        } else {
            $user->favorites()->attach($listing->id);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['favorited' => !$isFavorited]);
        }

        return back()->with('success', !$isFavorited ? 'Added to favorites.' : 'Removed from favorites.');
    }

    public function index()
    {
        $favorites = Auth::user()->favorites()->with(['media', 'city'])->paginate(12);
        return view('public.favorites', compact('favorites'));
    }
}