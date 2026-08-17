<?php

namespace Database\Seeders;

use App\Models\Listing\Category;
use Illuminate\Database\Seeder;

class DiscoverCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Gastronomy', 'slug' => 'gastronomy'],
            ['name' => 'Culture & Art', 'slug' => 'culture-art'],
            ['name' => 'Outdoor Activities', 'slug' => 'outdoor-activities'],
            ['name' => 'Rural Tourism', 'slug' => 'rural-tourism'],
            ['name' => 'Wellness & Health', 'slug' => 'wellness-health'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name']]
            );
        }
    }
}
