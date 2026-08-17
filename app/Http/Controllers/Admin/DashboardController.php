<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Business\Business;
use App\Models\Listing\Listing;
use App\Models\Review\Review;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_businesses' => Business::count(),
            'total_listings' => Listing::count(),
            'pending_listings' => Listing::where('status', 'submitted')->count(),
            'published_listings' => Listing::where('status', 'published')->count(),
            'total_reviews' => Review::count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
        ];

        $recentListings = Listing::with(['business', 'city'])
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentListings', 'recentUsers'));
    }
}
