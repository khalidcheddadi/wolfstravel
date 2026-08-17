<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 */
class CacheMetricsService
{
    private static $metrics = [
        'hits' => 0,
        'misses' => 0,
        'operations' => [],
    ];

    /**
     */
    public static function recordOperation(string $key, bool $hit, int $duration = 0)
    {
        static::$metrics['operations'][] = [
            'key' => $key,
            'hit' => $hit,
            'duration_ms' => $duration,
            'timestamp' => now(),
        ];

        if ($hit) {
            static::$metrics['hits']++;
        } else {
            static::$metrics['misses']++;
        }
    }

    /**
     */
    public static function getHitRatio(): float
    {
        $total = static::$metrics['hits'] + static::$metrics['misses'];
        if ($total === 0) {
            return 0;
        }

        return (static::$metrics['hits'] / $total) * 100;
    }

    /**
     */
    public static function getMetrics(): array
    {
        return [
            'hits' => static::$metrics['hits'],
            'misses' => static::$metrics['misses'],
            'hit_ratio' => static::getHitRatio() . '%',
            'total_operations' => count(static::$metrics['operations']),
            'average_duration_ms' => static::getAverageDuration(),
            'operations' => static::$metrics['operations'],
        ];
    }

    /**
     */
    private static function getAverageDuration(): float
    {
        if (empty(static::$metrics['operations'])) {
            return 0;
        }

        $total = array_sum(array_column(static::$metrics['operations'], 'duration_ms'));
        return $total / count(static::$metrics['operations']);
    }

    /**
     */
    public static function reset()
    {
        static::$metrics = [
            'hits' => 0,
            'misses' => 0,
            'operations' => [],
        ];
    }

    /**
     */
    public static function printReport()
    {
        $metrics = static::getMetrics();

        echo "\n╔════════════════════════════════════╗\n";
        echo "║ 📊 تقرير أداء الكاش\n";
        echo "╠════════════════════════════════════╣\n";
        echo "║ Hits: {$metrics['hits']}\n";
        echo "║ Misses: {$metrics['misses']}\n";
        echo "║ Hit Ratio: {$metrics['hit_ratio']}\n";
        echo "║ Average Duration: {$metrics['average_duration_ms']}ms\n";
        echo "║ Total Operations: {$metrics['total_operations']}\n";
        echo "╚════════════════════════════════════╝\n\n";
    }
}
