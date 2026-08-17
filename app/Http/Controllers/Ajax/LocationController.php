<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Location\Country;
use App\Models\Location\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    /**
     */
    public function storeCountry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200|unique:countries,name',
            'code' => 'nullable|string|max:3',
        ]);

        $country = Country::create([
            'name' => $request->name,
            'code' => strtoupper($request->code ?? substr($request->name, 0, 3)),
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'success' => true,
            'country' => [
                'id' => $country->id,
                'name' => $country->name,
            ],
        ]);
    }

    /**
     */
    public function storeCity(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'country_id' => 'required|exists:countries,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $city = City::updateOrCreate(
            [
                'name' => $request->name,
                'country_id' => $request->country_id,
            ],
            [
                'slug' => Str::slug($request->name) . '-' . Str::random(5),
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        return response()->json([
            'success' => true,
            'city' => [
                'id' => $city->id,
                'name' => $city->name,
            ],
        ]);
    }

    /**
     */
    public function getCities($countryId)
    {
        $cities = City::where('country_id', $countryId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }
}
