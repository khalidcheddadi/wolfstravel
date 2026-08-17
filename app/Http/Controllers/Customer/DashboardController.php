<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $favorites = $user->favorites()->with(['media', 'city', 'country'])->latest()->take(5)->get();
        $reviews = $user->reviews()->with('listing')->latest()->take(5)->get();

        $stats = [
            'favorites_count' => $user->favorites()->count(),
            'reviews_count' => $user->reviews()->count(),
        ];

        return view('customer.dashboard', compact('user', 'favorites', 'reviews', 'stats'));
    }
}
