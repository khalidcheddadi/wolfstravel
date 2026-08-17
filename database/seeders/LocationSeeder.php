<?php

namespace Database\Seeders;

use App\Models\Location\Country;
use App\Models\Location\City;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {

        $spain = Country::create([
            'name' => 'Spain',
            'code' => 'ESP',
            'slug' => 'spain'
        ]);

        City::create(['name' => 'Madrid', 'slug' => 'madrid', 'country_id' => $spain->id]);
        City::create(['name' => 'Barcelona', 'slug' => 'barcelona', 'country_id' => $spain->id]);
        City::create(['name' => 'Seville', 'slug' => 'seville', 'country_id' => $spain->id]);
        City::create(['name' => 'Granada', 'slug' => 'granada', 'country_id' => $spain->id]);
        City::create(['name' => 'Valencia', 'slug' => 'valencia', 'country_id' => $spain->id]);
        City::create(['name' => 'Málaga', 'slug' => 'malaga', 'country_id' => $spain->id]);
        City::create(['name' => 'Bilbao', 'slug' => 'bilbao', 'country_id' => $spain->id]);
        City::create(['name' => 'Palma de Mallorca', 'slug' => 'palma-de-mallorca', 'country_id' => $spain->id]);


        $france = Country::create(['name' => 'France', 'code' => 'FRA', 'slug' => 'france']);
        City::create(['name' => 'Paris', 'slug' => 'paris', 'country_id' => $france->id]);
        City::create(['name' => 'Nice', 'slug' => 'nice', 'country_id' => $france->id]);
        City::create(['name' => 'Lyon', 'slug' => 'lyon', 'country_id' => $france->id]);

        $italy = Country::create(['name' => 'Italy', 'code' => 'ITA', 'slug' => 'italy']);
        City::create(['name' => 'Rome', 'slug' => 'rome', 'country_id' => $italy->id]);
        City::create(['name' => 'Milan', 'slug' => 'milan', 'country_id' => $italy->id]);
        City::create(['name' => 'Florence', 'slug' => 'florence', 'country_id' => $italy->id]);
        City::create(['name' => 'Venice', 'slug' => 'venice', 'country_id' => $italy->id]);

        $uk = Country::create(['name' => 'United Kingdom', 'code' => 'GBR', 'slug' => 'united-kingdom']);
        City::create(['name' => 'London', 'slug' => 'london', 'country_id' => $uk->id]);
        City::create(['name' => 'Edinburgh', 'slug' => 'edinburgh', 'country_id' => $uk->id]);

        $germany = Country::create(['name' => 'Germany', 'code' => 'DEU', 'slug' => 'germany']);
        City::create(['name' => 'Berlin', 'slug' => 'berlin', 'country_id' => $germany->id]);
        City::create(['name' => 'Munich', 'slug' => 'munich', 'country_id' => $germany->id]);

        $portugal = Country::create(['name' => 'Portugal', 'code' => 'PRT', 'slug' => 'portugal']);
        City::create(['name' => 'Lisbon', 'slug' => 'lisbon', 'country_id' => $portugal->id]);
        City::create(['name' => 'Porto', 'slug' => 'porto', 'country_id' => $portugal->id]);

        
        $usa = Country::create(['name' => 'United States', 'code' => 'USA', 'slug' => 'united-states']);
        City::create(['name' => 'New York', 'slug' => 'new-york', 'country_id' => $usa->id]);
        City::create(['name' => 'Miami', 'slug' => 'miami', 'country_id' => $usa->id]);
        City::create(['name' => 'Los Angeles', 'slug' => 'los-angeles', 'country_id' => $usa->id]);

        $uae = Country::create(['name' => 'United Arab Emirates', 'code' => 'ARE', 'slug' => 'uae']);
        City::create(['name' => 'Dubai', 'slug' => 'dubai', 'country_id' => $uae->id]);
        City::create(['name' => 'Abu Dhabi', 'slug' => 'abu-dhabi', 'country_id' => $uae->id]);
    }
}
