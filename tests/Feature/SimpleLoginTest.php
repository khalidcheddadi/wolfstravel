<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

beforeEach(function () {
    User::firstOrCreate(
        ['email' => 'admin@trav.com'],
        [
            'name' => 'Admin Trav',
            'password' => bcrypt('password'),
        ]
    );
});

test('login page loads', function () {
    $response = $this->get('/login');
    expect($response->status())->toBe(200);
});

test('user can login with correct credentials', function () {
    $response = $this->post('/login', [
        'email' => 'admin@trav.com',
        'password' => 'password',
    ]);

    expect($response->status())->toBe(302);
    expect(Auth::check())->toBeTrue();
});

test('wrong credentials fail', function () {
    $response = $this->post('/login', [
        'email' => 'wrong@example.com',
        'password' => 'wrong',
    ]);

    expect($response->status())->toBe(302);
    expect(Auth::check())->toBeFalse();
});
