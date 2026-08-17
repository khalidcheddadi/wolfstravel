<?php

use Illuminate\Support\Facades\Redis;

test('Redis connection is alive', function () {
    try {
        $ping = Redis::ping();
        expect($ping)->toBe('PONG', ' Redis is not responding');
    } catch (\Exception $e) {
        throw new Exception('Failed to connect to Redis: ' . $e->getMessage());
    }
})->group('redis');

test('Redis can store and retrieve data', function () {
    try {
        Redis::set('test_key', 'test_value');
        $value = Redis::get('test_key');
        expect($value)->toBe('test_value', ' Redis does not store/retrieve data');
        Redis::del('test_key');
    } catch (\Exception $e) {
        throw new Exception('Redis test failed: ' . $e->getMessage());
    }
})->group('redis');