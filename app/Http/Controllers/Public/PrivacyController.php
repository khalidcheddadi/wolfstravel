<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Location\City;
use App\Models\Listing\Category;
use App\Models\Listing\Listing; 
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('name')->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        $listings = collect();

        return view('public.privacy', compact('cities', 'categories', 'listings'));
    }
}
