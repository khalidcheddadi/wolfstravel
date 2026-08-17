<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

/**
 */
class DatabaseOptimizationService
{
    /**
     */
    public static function withOptimizedRelations(Builder $query, array $relations): Builder
    {
        return $query->with($relations);
    }

    /**
     */
    public static function optimizedPaginate(
        Builder $query,
        int $perPage = 12,
        string $cursor = null,
        string $cursorColumn = 'id'
    ) {
        if ($cursor) {
            return $query->cursorPaginate($perPage, columns: ['*'], cursorName: 'cursor');
        }

        return $query->paginate($perPage);
    }

    /**
     */
    public static function selectColumns(Builder $query, array $columns = ['*']): Builder
    {
        if ($columns === ['*']) {
            return $query;
        }

        if (!in_array('id', $columns)) {
            $columns[] = 'id';
        }

        return $query->select($columns);
    }

    /**
     */
    public static function optimizedCount(Builder $query): int
    {
        return $query->count();
    }

    /**
     */
    public static function enableQueryLogging()
    {
        \DB::enableQueryLog();
    }

    /**
     */
    public static function getQueryStats(): array
    {
        $queries = \DB::getQueryLog();

        $stats = [
            'total_queries' => count($queries),
            'total_time_ms' => 0,
            'slowest_queries' => [],
        ];

        foreach ($queries as $query) {
            $stats['total_time_ms'] += $query['time'];
            $stats['slowest_queries'][] = [
                'query' => $query['query'],
                'time' => $query['time'] . 'ms',
                'bindings' => $query['bindings'],
            ];
        }

        usort($stats['slowest_queries'], function ($a, $b) {
            return floatval($b['time']) <=> floatval($a['time']);
        });

        $stats['slowest_queries'] = array_slice($stats['slowest_queries'], 0, 5);

        return $stats;
    }

    /**
     */
    public static function checkExists(Builder $query, string $column = null): bool
    {
        return $query->exists();
    }

    /**
     */
    public static function optimizedWhereIn(Builder $query, string $column, array $values, int $chunkSize = 1000)
    {
        if (count($values) <= $chunkSize) {
            return $query->whereIn($column, $values);
        }

        return $query->where(function ($q) use ($column, $values, $chunkSize) {
            $chunks = array_chunk($values, $chunkSize);
            foreach ($chunks as $chunk) {
                $q->orWhereIn($column, $chunk);
            }
        });
    }
}
