<?php

namespace App\Http\Controllers\BusinessOwner;

use App\Http\Controllers\Controller;
use App\Models\Business\Business;
use App\Models\Business\BusinessType;
use App\Models\Location\Country;
use App\Models\Location\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        if ($user->businesses()->exists()) {
            return redirect()->route('business-owner.business.edit');
        }

        return view('business-owner.business.create', [
            'businessTypes' => BusinessType::all(),
            'countries' => Country::orderBy('name')->get(),
            'cities' => City::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:200',
            'business_type_id' => 'required|exists:business_types,id',
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:30',
            'website' => 'nullable|url',
        ]);

        $user = Auth::user();

        $business = Business::create([
            'uuid' => (string) Str::uuid(),
            'owner_id' => $user->id,
            'business_name' => $request->business_name,
            'slug' => Str::slug($request->business_name) . '-' . Str::random(6),
            'business_type_id' => $request->business_type_id,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'phone' => $request->phone,
            'website' => $request->website,
            'verified' => false,
            'status' => 'active',
        ]);

        return redirect()
            ->route('business-owner.dashboard')
            ->with('success', 'The facility was successfully created!');
    }

    public function edit()
    {
        $user = Auth::user();
        $business = $user->businesses()->firstOrFail();

        return view('business-owner.business.edit', [
            'business' => $business,
            'businessTypes' => BusinessType::all(),
            'countries' => Country::orderBy('name')->get(),
            'cities' => City::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $business = $user->businesses()->firstOrFail();

        $request->validate([
            'business_name' => 'required|string|max:200',
            'business_type_id' => 'required|exists:business_types,id',
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:30',
            'website' => 'nullable|url',
        ]);

        $business->update([
            'business_name' => $request->business_name,
            'business_type_id' => $request->business_type_id,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'phone' => $request->phone,
            'website' => $request->website,
        ]);

        return redirect()
            ->route('business-owner.dashboard')
            ->with('success', 'The facility has been successfully updated!');
    }
}
