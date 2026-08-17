<?php

namespace App\Http\Middleware;

use App\Services\PerformanceMonitoringService;
use App\Services\ResponseOptimizationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerformanceOptimization
{
    public function handle(Request $request, Closure $next): Response
    {
        PerformanceMonitoringService::startTimer('request');
        \DB::enableQueryLog();
        PerformanceMonitoringService::checkpoint('request', 'start');

        $response = $next($request);

        PerformanceMonitoringService::checkpoint('request', 'after_handler');

        if ($request->method() === 'GET') {
            $ttl = $this->isPublicPage($request) ? 3600 : 300;
            $public = $this->isPublicPage($request);
            ResponseOptimizationService::addCacheHeaders($response, $ttl, $public);
        }

        ResponseOptimizationService::addSecurityHeaders($response);

        if ($this->shouldCompress($response)) {
            ResponseOptimizationService::enableCompressionHeaders($response);
        }

        PerformanceMonitoringService::checkpoint('request', 'headers_added');

        $metrics = PerformanceMonitoringService::endTimer('request');
        $response->headers->set('X-Response-Time', $metrics['total_ms'] . 'ms');
        $response->headers->set('X-DB-Queries', PerformanceMonitoringService::getQueryCount());
        $response->headers->set('X-Cache-Hit-Ratio', PerformanceMonitoringService::getCacheHitRatio());

        if ($metrics['total_ms'] > 500) {
            $this->logSlowRequest($request, $metrics);
        }

        return $response;
    }

    private function isPublicPage(Request $request): bool
    {
        $publicRoutes = ['listing.show', 'category.show', 'home', 'search'];

        foreach ($publicRoutes as $route) {
            if ($request->routeIs($route)) {
                return true;
            }
        }

        return false;
    }

    private function shouldCompress(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');

        return str_contains($contentType, 'application/json')
            || str_contains($contentType, 'text/html')
            || str_contains($contentType, 'application/javascript');
    }

    private function logSlowRequest(Request $request, array $metrics): void
    {
        \Log::warning('Slow Request Detected', [
            'url' => $request->url(),
            'method' => $request->method(),
            'response_time_ms' => $metrics['total_ms'],
            'query_count' => PerformanceMonitoringService::getQueryCount(),
            'memory_usage_mb' => PerformanceMonitoringService::getMemoryUsage()['current_mb'],
            'status' => $metrics['status'],
        ]);
    }
}
