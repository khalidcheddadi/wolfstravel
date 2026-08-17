<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class OptimizeCacheCommand extends Command
{
    protected $signature = 'cache:optimize
        {action : Action to perform (clear|warm|stats|redis-check)}
        {--key= : Specific cache key to clear}
        {--all : Clear all cache}';

    protected $description = 'Cache optimization and management commands';

    public function handle()
    {
        $action = $this->argument('action');

        match ($action) {
            'clear' => $this->clearCache(),
            'warm' => $this->warmCache(),
            'stats' => $this->showStats(),
            'redis-check' => $this->checkRedis(),
            default => $this->error("Action '{$action}' is unknown"),
        };
    }

    private function clearCache()
    {
        if ($this->option('all')) {
            Cache::flush();
            $this->info('All cache cleared');
        } elseif ($key = $this->option('key')) {
            Cache::forget($key);
            $this->info("Cache cleared: {$key}");
        } else {
            CacheService::clearSearchCache();
            $this->info('Search and filter cache cleared');
        }
    }

    private function warmCache()
    {
        $this->info('Warming up cache...');

        CacheService::getCities();
        $this->line('Cities');

        CacheService::getMainCategories();
        $this->line('Categories');

        CacheService::getAllFeatures();
        $this->line('Features');

        CacheService::getFeaturedListings();
        $this->line('Featured listings');

        $this->info('Cache warmed up successfully');
    }

    private function showStats()
    {
        $store = Cache::getStore();
        $driver = config('cache.default');

        $this->info("\nCache statistics:");
        $this->table(['Property', 'Value'], [
            ['Driver', $driver],
            ['Store', get_class($store)],
        ]);

        if ($driver === 'redis') {
            try {
                $redis = $store->connection();
                $info = $redis->info();

                $this->info("\nRedis Information:");
                $this->table(['Key', 'Value'], [
                    ['Used Memory', $info['used_memory_human']],
                    ['Connected Clients', $info['connected_clients']],
                    ['Total Keys', $redis->dbsize()],
                ]);
            } catch (\Exception $e) {
                $this->error('Unable to connect to Redis');
            }
        }
    }

    private function checkRedis()
    {
        try {
            $redis = Cache::getStore()->connection();
            $redis->ping();
            $this->info('Redis connected and working properly');
        } catch (\Exception $e) {
            $this->error('Failed to connect to Redis');
            $this->line($e->getMessage());
        }
    }
}
