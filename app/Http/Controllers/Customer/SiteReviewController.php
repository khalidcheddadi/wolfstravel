<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SiteReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $myReviews = $user->siteReviews()->latest()->paginate(10);
        $recentReviews = SiteReview::approved()->with('user')->latest()->take(6)->get();

        return view('customer.reviews.index', compact('user', 'myReviews', 'recentReviews'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string', 'min:10', 'max:1500'],
        ]);

        Auth::user()->siteReviews()->create([
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'],
            'status' => 'approved',
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'تمت إضافة تقييمك بنجاح وسيظهر في صفحة التجارب.');
    }
}
