<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'business_owner']);
        Role::firstOrCreate(['name' => 'customer']);
        Role::firstOrCreate(['name' => 'moderator']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@trav.com'],
            [
                'name' => 'Admin Trav',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');
    }
}
