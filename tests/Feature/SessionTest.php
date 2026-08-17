<?php

use Illuminate\Support\Facades\Session;

test('session can store and retrieve data', function () {
    Session::put('test_session', 'hello');
    expect(Session::get('test_session'))->toBe('hello', 'Session does not store data');
    Session::forget('test_session');
})->group('session');

test('session domain is correct', function () {
    $domain = config('session.domain');
    if ($domain !== null) {
        expect($domain)->toBeIn(['localhost', '.trav.com'], 'SESSION_DOMAIN is invalid');
    }
})->group('session');