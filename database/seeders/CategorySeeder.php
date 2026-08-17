<?php

namespace Database\Seeders;

use App\Models\Listing\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {

        $luxury = Category::create(['name' => 'Luxury', 'slug' => 'luxury']);
        $family = Category::create(['name' => 'Family Friendly', 'slug' => 'family-friendly']);
        $adventure = Category::create(['name' => 'Adventure', 'slug' => 'adventure']);
        $romantic = Category::create(['name' => 'Romantic', 'slug' => 'romantic']);
        $budget = Category::create(['name' => 'Budget', 'slug' => 'budget']);
        $cultural = Category::create(['name' => 'Cultural', 'slug' => 'cultural']);
        $eco = Category::create(['name' => 'Eco-friendly', 'slug' => 'eco-friendly']);
        $sport = Category::create(['name' => 'Sport', 'slug' => 'sport']);


        Category::create(['name' => '5-Star Hotels', 'slug' => '5-star-hotels', 'parent_id' => $luxury->id]);
        Category::create(['name' => 'Private Tours', 'slug' => 'private-tours', 'parent_id' => $luxury->id]);
        Category::create(['name' => 'Fine Dining', 'slug' => 'fine-dining', 'parent_id' => $luxury->id]);

        Category::create(['name' => 'Hiking', 'slug' => 'hiking', 'parent_id' => $adventure->id]);
        Category::create(['name' => 'Water Sports', 'slug' => 'water-sports', 'parent_id' => $adventure->id]);
        Category::create(['name' => 'Safari', 'slug' => 'safari', 'parent_id' => $adventure->id]);


        Category::create(['name' => 'Theme Parks', 'slug' => 'theme-parks', 'parent_id' => $family->id]);
        Category::create(['name' => 'Zoos & Aquariums', 'slug' => 'zoos-aquariums', 'parent_id' => $family->id]);
        Category::create(['name' => 'Family Resorts', 'slug' => 'family-resorts', 'parent_id' => $family->id]);


        Category::create(['name' => 'Historical Landmarks', 'slug' => 'historical-landmarks', 'parent_id' => $cultural->id]);
        Category::create(['name' => 'Art Galleries', 'slug' => 'art-galleries', 'parent_id' => $cultural->id]);
        Category::create(['name' => 'Local Festivals', 'slug' => 'local-festivals', 'parent_id' => $cultural->id]);

        
        Category::create(['name' => 'Football', 'slug' => 'football', 'parent_id' => $sport->id]);
        Category::create(['name' => 'Cycling', 'slug' => 'cycling', 'parent_id' => $sport->id]);
        Category::create(['name' => 'Golf', 'slug' => 'golf', 'parent_id' => $sport->id]);
    }
}
