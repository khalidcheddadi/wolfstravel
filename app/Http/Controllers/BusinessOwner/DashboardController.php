<?php

namespace App\Http\Controllers\BusinessOwner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $business = $user->businesses()->first();

        if (!$business) {
            return view('business-owner.dashboard', [
                'business' => null,
                'user' => $user,
                'stats' => null,
                'recentListings' => collect(),
            ]);
        }

        $stats = [
            'total_listings' => $business->listings()->count(),
            'published_listings' => $business->listings()->where('status', 'published')->count(),
            'pending_listings' => $business->listings()->whereIn('status', ['draft', 'submitted'])->count(),
            'total_views' => $business->listings()->sum('views'),
            'average_rating' => $business->listings()->avg('average_rating') ?? 0,
        ];

        $recentListings = $business->listings()
            ->with(['media', 'city', 'country'])
            ->latest()
            ->take(5)
            ->get();

        return view('business-owner.dashboard', compact('business', 'user', 'stats', 'recentListings'));
    }
}
