<?php

namespace Database\Seeders;

use App\Models\Listing\ListingFeature;
use Illuminate\Database\Seeder;

class ListingFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            ['name' => 'Wi-Fi', 'slug' => 'wifi', 'icon' => 'fa-wifi'],
            ['name' => 'Parking', 'slug' => 'parking', 'icon' => 'fa-parking'],
            ['name' => 'Air Conditioning', 'slug' => 'air-conditioning', 'icon' => 'fa-snowflake'],
            ['name' => 'Swimming Pool', 'slug' => 'swimming-pool', 'icon' => 'fa-swimming-pool'],
            ['name' => 'Restaurant', 'slug' => 'restaurant', 'icon' => 'fa-utensils'],
            ['name' => 'Sea View', 'slug' => 'sea-view', 'icon' => 'fa-water'],
            ['name' => 'Room Service', 'slug' => 'room-service', 'icon' => 'fa-concierge-bell'],
            ['name' => '24-Hour Reception', 'slug' => '24-hour-reception', 'icon' => 'fa-clock'],
            ['name' => 'Guided Tours', 'slug' => 'guided-tours', 'icon' => 'fa-route'],
            ['name' => 'Equipment Rental', 'slug' => 'equipment-rental', 'icon' => 'fa-tools'],
            ['name' => 'Pet Friendly', 'slug' => 'pet-friendly', 'icon' => 'fa-dog'],
            ['name' => 'Spa & Wellness', 'slug' => 'spa-wellness', 'icon' => 'fa-spa'],
            ['name' => 'Family Friendly', 'slug' => 'family-friendly', 'icon' => 'fa-child'],
            ['name' => 'Accessibility', 'slug' => 'accessibility', 'icon' => 'fa-wheelchair'],
            ['name' => 'Outdoor Terrace', 'slug' => 'outdoor-terrace', 'icon' => 'fa-umbrella'],
            ['name' => 'Live Entertainment', 'slug' => 'live-entertainment', 'icon' => 'fa-music'],
        ];

        foreach ($features as $feature) {
            ListingFeature::create($feature);
        }
    }
}
