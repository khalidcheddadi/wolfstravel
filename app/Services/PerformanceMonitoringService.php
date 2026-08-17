<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class PerformanceMonitoringService
{
    private static array $metrics = [];

    public static function startTimer(string $name = 'default'): void
    {
        self::$metrics[$name] = [
            'start' => microtime(true),
            'checkpoints' => [],
        ];
    }

    public static function checkpoint(string $name, string $point): void
    {
        if (! isset(self::$metrics[$name])) {
            self::startTimer($name);
        }

        $elapsed = (microtime(true) - self::$metrics[$name]['start']) * 1000;

        self::$metrics[$name]['checkpoints'][] = [
            'point' => $point,
            'elapsed_ms' => round($elapsed, 2),
            'timestamp' => now(),
        ];
    }

    public static function endTimer(string $name = 'default'): array
    {
        if (! isset(self::$metrics[$name])) {
            return [];
        }

        $duration = (microtime(true) - self::$metrics[$name]['start']) * 1000;
        $result = array_merge(self::$metrics[$name], [
            'total_ms' => round($duration, 2),
            'status' => self::getPerformanceStatus($duration),
        ]);

        self::logMetrics($name, $result);

        return $result;
    }

    private static function getPerformanceStatus(float $durationMs): string
    {
        if ($durationMs < 100) {
            return 'Excellent';
        }

        if ($durationMs < 200) {
            return 'Good';
        }

        if ($durationMs < 500) {
            return 'Fair';
        }

        if ($durationMs < 1000) {
            return 'Poor';
        }

        return 'Very Poor';
    }

    public static function getMemoryUsage(): array
    {
        return [
            'current_mb' => round(memory_get_usage() / 1024 / 1024, 2),
            'peak_mb' => round(memory_get_peak_usage() / 1024 / 1024, 2),
            'limit_mb' => ini_get('memory_limit'),
        ];
    }

    public static function getQueryCount(): int
    {
        return count(\DB::getQueryLog());
    }

    public static function analyzeSlowQueries(float $threshold = 100): array
    {
        $slowQueries = [];

        foreach (\DB::getQueryLog() as $query) {
            if ($query['time'] >= $threshold) {
                $slowQueries[] = [
                    'query' => $query['query'],
                    'time_ms' => $query['time'],
                    'severity' => $query['time'] > 500 ? 'Critical' : 'Warning',
                ];
            }
        }

        usort($slowQueries, fn ($a, $b) => $b['time_ms'] <=> $a['time_ms']);

        return array_slice($slowQueries, 0, 10);
    }

    public static function generatePerformanceReport(): array
    {
        \DB::enableQueryLog();

        return [
            'timestamp' => now(),
            'memory' => self::getMemoryUsage(),
            'queries' => [
                'total' => self::getQueryCount(),
                'slow_queries' => self::analyzeSlowQueries(),
            ],
            'timers' => self::$metrics,
            'metrics' => [
                'cache_hit_ratio' => self::getCacheHitRatio(),
                'response_size_kb' => 0,
            ],
        ];
    }

    public static function getCacheHitRatio(): string
    {
        try {
            $redis = \Cache::getStore()->connection();
            $info = $redis->info();

            if (isset($info['keyspace_hits']) && isset($info['keyspace_misses'])) {
                $total = $info['keyspace_hits'] + $info['keyspace_misses'];

                if ($total === 0) {
                    return '0%';
                }

                $ratio = ($info['keyspace_hits'] / $total) * 100;

                return round($ratio, 2) . '%';
            }
        } catch (\Exception $e) {
            // No Redis available
        }

        return 'N/A';
    }

    private static function logMetrics(string $name, array $result): void
    {
        Log::channel('performance')->info("Performance Metrics for [$name]", $result);
    }

    public static function exportMetrics(array $metrics, string $service = 'datadog'): void
    {
        Log::info("Exporting metrics to {$service}", $metrics);
    }
}
