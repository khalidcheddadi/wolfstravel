<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(BusinessTypeSeeder::class);
        $this->call(ListingTypeSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ListingFeatureSeeder::class);
    }
}
