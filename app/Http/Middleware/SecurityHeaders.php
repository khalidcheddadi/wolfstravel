<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $this->applySecurityHeaders($response);
        $this->applyCachingHeaders($request, $response);

        return $response;
    }

    private function applySecurityHeaders(Response $response): void
    {
        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'geolocation=(self), camera=(), microphone=()',
            'Cross-Origin-Opener-Policy' => 'same-origin',
            'Cross-Origin-Resource-Policy' => 'same-origin',
            'Cross-Origin-Embedder-Policy' => 'unsafe-none',
            'X-Permitted-Cross-Domain-Policies' => 'none',
            'X-Download-Options' => 'noopen',
            'X-DNS-Prefetch-Control' => 'off',
            'Content-Security-Policy' => $this->buildCspPolicy(),
        ];

        if (app()->environment('production')) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains; preload';
        }

        foreach ($headers as $name => $value) {
            $response->headers->set($name, $value);
        }
    }

    private function applyCachingHeaders(Request $request, Response $response): void
    {
        if (! $this->shouldDisableCaching($request)) {
            return;
        }

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, private, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
    }

    private function buildCspPolicy(): string
    {
        $policy = [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'none'",
            "form-action 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com http://127.0.0.1:5173 http://localhost:5173 http://[::1]:5173",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://unpkg.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net http://127.0.0.1:5173 http://localhost:5173 http://[::1]:5173",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: https: http: https://*.tile.openstreetmap.org https://unpkg.com https://ui-avatars.com https://flagcdn.com https://images.unsplash.com https://*.cloudinary.com",
            "frame-src 'self' https://www.google.com",
            "media-src 'self' https:",
        ];

        $connectSrc = app()->environment('production')
            ? "connect-src 'self' https://meilisearch.yourdomain.com https://unpkg.com https://cdn.jsdelivr.net"
            : "connect-src 'self' http://127.0.0.1:7700 https://unpkg.com https://cdn.jsdelivr.net ws://127.0.0.1:5173 ws://localhost:5173 ws://[::1]:5173";

        $policy[] = $connectSrc;

        return implode('; ', $policy) . ';';
    }

    private function shouldDisableCaching(Request $request): bool
    {
        if ($request->user()) {
            return true;
        }

        return $request->routeIs([
            'login',
            'register',
            'password.*',
            'verification.*',
            'admin.*',
            'business-owner.*',
            'customer.*',
            'profile.*',
            'dashboard',
            'favorites.*',
            'ajax.*',
            'contact.submit',
        ]);
    }
}
