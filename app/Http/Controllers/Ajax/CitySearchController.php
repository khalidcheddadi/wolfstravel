<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CitySearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $countryCode = $request->input('country_code');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $url = 'https://nominatim.openstreetmap.org/search';
        $params = [
            'q' => $query,
            'format' => 'json',
            'limit' => 10,
            'addressdetails' => 1,
            'featuretype' => 'city',
            'accept-language' => 'en',
        ];

        if (!empty($countryCode)) {
            $params['countrycodes'] = $countryCode;
        }

        $response = Http::withHeaders([
            'User-Agent' => 'wolfstravel App (contact@wolfstravel.com)'
        ])->get($url, $params);

        if ($response->failed()) {
            return response()->json([]);
        }

        $results = $response->json();
        $cities = [];

        foreach ($results as $item) {
            $address = $item['address'] ?? [];
            $cityName = $address['city'] ?? $address['town'] ?? $address['village'] ?? $address['municipality'] ?? $item['display_name'];
            $country = $address['country'] ?? '';

            $lat = $item['lat'] ?? null;
            $lng = $item['lon'] ?? null;

            $key = $cityName . '-' . $country;
            if (!isset($cities[$key])) {
                $cities[$key] = [
                    'name' => $cityName,
                    'country' => $country,
                    'latitude' => $lat,
                    'longitude' => $lng,
                ];
            }
        }

        return response()->json(array_values($cities));
    }
}
