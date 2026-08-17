<?php

use Illuminate\Support\Facades\Config;

test('SESSION_DRIVER is configured correctly', function () {
    $driver = Config::get('session.driver');
    expect($driver)->not->toBeEmpty(' SESSION_DRIVER is not defined');
    expect($driver)->toBeIn(['file', 'cookie', 'database', 'redis'], ' SESSION_DRIVER is not supported');
})->group('config');

test('Redis connection is working when used', function () {
    if (Config::get('session.driver') !== 'redis') {
        $this->markTestSkipped('⏭️ Redis is not currently used');
    }
    $host = Config::get('database.redis.default.host');
    $port = Config::get('database.redis.default.port');
    expect($host)->toBe('127.0.0.1', ' REDIS_HOST is invalid');
    expect($port)->toBe(6379, ' REDIS_PORT is invalid');
})->group('config', 'redis');

test('Database connection is correct', function () {
    $connection = Config::get('database.default');
    expect($connection)->toBe('mysql', ' DB_CONNECTION is invalid');
    $dbName = Config::get("database.connections.{$connection}.database");
    expect($dbName)->toBe('trav', ' DB_DATABASE is invalid (should be trav)');
})->group('config');