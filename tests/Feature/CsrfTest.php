<?php

test('CSRF token exists in login form', function () {
    $response = $this->get('/login');
    $content = $response->getContent();
    expect($content)->toContain('csrf_token', '@csrf is missing in the login form');
})->group('csrf');

test('request without CSRF token returns 419', function () {
    $response = $this->post('/login', [
        'email' => 'admin@trav.com',
        'password' => 'password',
    ], ['X-CSRF-TOKEN' => '']);

    expect($response->status())->toBe(419);
})->group('csrf');

test('CSRF is not blocking login when token is valid', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    expect($response->status())->not->toBe(419);
})->group('csrf');