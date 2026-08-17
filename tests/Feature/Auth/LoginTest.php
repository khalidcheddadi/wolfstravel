<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'business_owner']);
    Role::firstOrCreate(['name' => 'customer']);
    Role::firstOrCreate(['name' => 'moderator']);
});

test('login page can be displayed', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
})->group('auth');

test('user can login with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('customer');

    $this->get('/login');

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password',
        '_token' => csrf_token(),
    ]);

    expect($response->status())->toBe(302);
    expect(auth()->check())->toBeTrue();
})->group('auth');

test('user fails login with wrong credentials', function () {
    $this->get('/login');

    $response = $this->post('/login', [
        'email' => 'wrong@example.com',
        'password' => 'wrongpassword',
        '_token' => csrf_token(),
    ]);

    expect($response->status())->toBe(302);
    expect(auth()->check())->toBeFalse();
})->group('auth');

test('admin is redirected to admin dashboard', function () {
    $user = User::factory()->create([
        'email' => 'admin@trav.com',
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('admin');

    $this->get('/login');

    $response = $this->post('/login', [
        'email' => 'admin@trav.com',
        'password' => 'password',
        '_token' => csrf_token(),
    ]);

    $response->assertRedirect('/admin/dashboard');
})->group('auth');
