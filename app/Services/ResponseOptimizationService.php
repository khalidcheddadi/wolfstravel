<?php

namespace App\Services;

use Illuminate\Http\Response;

class ResponseOptimizationService
{
    public static function addCacheHeaders(
        Response $response,
        int $maxAge = 3600,
        bool $isPublic = true,
        bool $allowStale = true
    ): Response {
        $cacheControl = $isPublic ? 'public' : 'private';
        $cacheControl .= ", max-age={$maxAge}";

        if ($allowStale) {
            $cacheControl .= ', stale-while-revalidate=86400';
            $cacheControl .= ', stale-if-error=604800';
        }

        $response->headers->set('Cache-Control', $cacheControl);
        $response->headers->set('ETag', md5($response->getContent()));

        return $response;
    }

    public static function enableCompressionHeaders(Response $response): Response
    {
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Vary', 'Accept-Encoding');

        return $response;
    }

    public static function optimizedJsonResponse(
        array $data,
        int $statusCode = 200,
        array $headers = [],
        array $meta = []
    ): \Illuminate\Http\JsonResponse {
        $response = [
            'success' => $statusCode >= 200 && $statusCode < 300,
            'data' => $data,
        ];

        if (! empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $statusCode, [
            'Cache-Control' => 'public, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
            ...$headers,
        ]);
    }

    public static function paginatedJsonResponse($data, $meta = null)
    {
        if ($data instanceof \Illuminate\Pagination\Paginator) {
            return response()->json([
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'last_page' => $data->lastPage(),
                    'has_more' => $data->hasMorePages(),
                ],
                'meta' => $meta,
            ])->header('Cache-Control', 'public, max-age=300');
        }

        return response()->json(['data' => $data]);
    }

    public static function handleConditionalRequest($lastModified = null, $etag = null)
    {
        if ($lastModified) {
            header("Last-Modified: {$lastModified}");
        }

        if ($etag) {
            header("ETag: {$etag}");
        }

        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] === $etag) {
            http_response_code(304);
            exit;
        }
    }

    public static function getResponseSize($data): int
    {
        return strlen(json_encode($data));
    }

    public static function addSecurityHeaders(Response $response): Response
    {
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
