<?php

namespace App\Console\Commands;

use App\Services\PerformanceMonitoringService;
use Illuminate\Console\Command;

class PerformanceAuditCommand extends Command
{
    protected $signature = 'performance:audit
        {action : Action to perform (analyze|report|indexes|fix)}
        {--detailed : Show detailed report}
        {--threshold=500 : Slow query threshold in ms}';

    protected $description = 'Application performance audit and optimization';

    public function handle()
    {
        $action = $this->argument('action');

        match ($action) {
            'analyze' => $this->analyzePerformance(),
            'report' => $this->generateReport(),
            'indexes' => $this->checkIndexes(),
            'fix' => $this->suggestFixes(),
            default => $this->error("Action '{$action}' is unknown"),
        };
    }

    private function analyzePerformance()
    {
        $this->info('Analyzing performance...');

        \DB::enableQueryLog();

        $listings = \App\Models\Listing\Listing::with([
            'media', 'city.translations', 'country', 'categories.translations'
        ])->where('status', 'published')->take(10)->get();

        $queries = \DB::getQueryLog();
        $threshold = (int)$this->option('threshold');

        $slowQueries = array_filter($queries, fn($q) => $q['time'] >= $threshold);

        $this->table(
            ['Number', 'Query', 'Duration (ms)', 'Severity'],
            collect($slowQueries)
                ->take(10)
                ->map(fn($q, $i) => [
                    $i + 1,
                    substr($q['query'], 0, 50) . '...',
                    $q['time'],
                    $q['time'] > 1000 ? 'Critical' : 'Warning',
                ])
                ->toArray()
        );

        $this->newLine();
        $this->info("Total queries: " . count($queries));
        $this->info("Slow queries: " . count($slowQueries));
    }

    private function generateReport()
    {
        $this->info('Generating performance report...');

        $report = PerformanceMonitoringService::generatePerformanceReport();

        $this->newLine();
        $this->info('━━━━━━━━━━━━━━━━━ Performance Report ━━━━━━━━━━━━━━━━━');
        $this->newLine();

        $this->table(
            ['Property', 'Value'],
            [
                ['Memory Used', $report['memory']['current_mb'] . ' MB'],
                ['Peak Memory', $report['memory']['peak_mb'] . ' MB'],
                ['Memory Limit', $report['memory']['limit_mb']],
            ]
        );

        $this->newLine();
        $this->info('Queries:');
        $this->table(
            ['Property', 'Value'],
            [
                ['Total Queries', $report['queries']['total']],
                ['Cache Hit Ratio', $report['metrics']['cache_hit_ratio']],
            ]
        );

        if (!empty($report['queries']['slow_queries'])) {
            $this->newLine();
            $this->info('Slow queries:');
            $this->table(
                ['Query', 'Duration (ms)', 'Severity'],
                collect($report['queries']['slow_queries'])->map(fn($q) => [
                    substr($q['query'], 0, 50) . '...',
                    $q['time_ms'],
                    $q['severity'],
                ])->toArray()
            );
        }
    }

    private function checkIndexes()
    {
        $this->info('Checking database indexes...');

        $missingIndexes = [
            'listings' => [
                'idx_status_published' => '(status, published_at)',
                'idx_city_id' => '(city_id)',
                'idx_average_rating' => '(average_rating)',
                'idx_views' => '(views)',
            ],
            'listing_prices' => [
                'idx_listing_price' => '(listing_id, price)',
            ],
            'favorites' => [
                'idx_user_listing' => '(user_id, listing_id)',
            ],
        ];

        $this->table(
            ['Table', 'Missing Index', 'Columns'],
            collect($missingIndexes)
                ->flatMap(fn($indexes, $table) =>
                    collect($indexes)->map(fn($columns, $name) => [$table, $name, $columns])
                )
                ->toArray()
        );

        $this->newLine();
        $this->warn('Run this command to add indexes:');
        $this->line('php artisan migrate --path=database/migrations/indexes_optimization.php');
    }

    private function suggestFixes()
    {
        $this->info('Optimization suggestions:');

        $suggestions = [
            '1. Enable Redis Cache' => 'CACHE_STORE=redis in .env',
            '2. Add Database Indexes' => 'php artisan migrate --path=database/migrations/indexes_optimization.php',
            '3. Enable Query Logging' => 'DB_LOG=true in .env',
            '4. Use Cursor Pagination' => 'Instead of Offset Pagination',
            '5. Optimize Images' => 'Use WebP and Responsive Images',
            '6. Enable Gzip Compression' => 'in Nginx/Apache',
            '7. Use CDN' => 'for static files',
            '8. Enable HTTP/2' => 'to improve load time',
        ];

        foreach ($suggestions as $title => $description) {
            $this->line("• <fg=cyan>$title</> - <fg=gray>$description</>");
        }

        $this->newLine();
        $this->info('Check PERFORMANCE_STRATEGY.txt for a complete guide');
    }
}
