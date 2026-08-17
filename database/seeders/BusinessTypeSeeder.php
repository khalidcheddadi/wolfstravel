<?php

namespace Database\Seeders;

use App\Models\Business\BusinessType;
use Illuminate\Database\Seeder;

class BusinessTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Hotel', 'slug' => 'hotel'],
            ['name' => 'Restaurant', 'slug' => 'restaurant'],
            ['name' => 'Travel Agency', 'slug' => 'travel-agency'],
            ['name' => 'Resort', 'slug' => 'resort'],
            ['name' => 'Museum', 'slug' => 'museum'],
            ['name' => 'Tour Operator', 'slug' => 'tour-operator'],
            ['name' => 'Activity Center', 'slug' => 'activity-center'],
            ['name' => 'Spa & Wellness', 'slug' => 'spa-wellness'],
            ['name' => 'Theme Park', 'slug' => 'theme-park'],
            ['name' => 'Cultural Center', 'slug' => 'cultural-center'],
        ];

        foreach ($types as $type) {
            BusinessType::create($type);
        }
    }
}
