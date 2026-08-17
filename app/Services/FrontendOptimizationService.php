<?php

namespace App\Services;

/**
 */
class FrontendOptimizationService
{
    /**
     */
    public static function optimizeImage(
        string $imagePath,
        array $sizes = ['small' => 300, 'medium' => 600, 'large' => 1200]
    ): array {
        $optimized = [];

        foreach ($sizes as $name => $width) {
            $optimized[$name] = [
                'webp' => "storage/optimized/{$name}.webp",
                'jpg' => "storage/optimized/{$name}.jpg",
                'width' => $width,
            ];
        }

        return $optimized;
    }

    /**
     */
    public static function lazyLoadImage(string $imagePath, string $alt = '', string $placeholder = null): string
    {
        $placeholder = $placeholder ?? 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"%3E%3Crect fill="%23f0f0f0"/%3E%3C/svg%3E';

        return <<<HTML
        <img
            src="$placeholder"
            data-src="$imagePath"
            alt="$alt"
            class="lazy-load"
            loading="lazy"
            decoding="async"
        />
        HTML;
    }

    /**
     */
    public static function responsiveImageSrcSet(string $imagePath, array $breakpoints = []): string
    {
        if (empty($breakpoints)) {
            $breakpoints = [320, 640, 960, 1280, 1920];
        }

        $srcSet = [];
        foreach ($breakpoints as $width) {
            $srcSet[] = "storage/responsive/{$width}w.webp {$width}w";
        }

        return implode(', ', $srcSet);
    }

    /**
     */
    public static function prefetchResources(array $urls = []): string
    {
        $links = [];

        $links[] = '<link rel="dns-prefetch" href="//cdn.example.com" />';

        $links[] = '<link rel="preconnect" href="//fonts.googleapis.com" />';

        foreach ($urls as $url) {
            $links[] = "<link rel=\"prefetch\" href=\"$url\" />";
        }

        return implode("\n", $links);
    }

    /**
     */
    public static function asyncScriptTag(string $src, bool $defer = true): string
    {
        $attrs = $defer ? 'defer async' : 'async';
        return "<script src=\"$src\" $attrs></script>";
    }

    /**
     * Code Splitting Configuration
     */
    public static function getCodeSplittingConfig(): array
    {
        return [
            'main' => [
                'size' => '45KB',
                'delay' => '0ms',
            ],
            'page' => [
                'size' => '30KB',
                'delay' => '100ms',
            ],
            'vendor' => [
                'size' => '100KB',
                'delay' => '200ms',
            ],
            'ui' => [
                'size' => '25KB',
                'delay' => '300ms',
            ],
        ];
    }

    /**
     */
    public static function optimizedFontLoading(): string
    {
        return <<<HTML
        <link rel="preload" as="font" href="/fonts/main.woff2" type="font/woff2" crossorigin />
        <style>
            @font-face {
                font-family: 'Main';
                src: url('/fonts/main.woff2') format('woff2');
                font-display: swap;
            }
        </style>
        HTML;
    }

    /**
     * Core Web Vitals Monitoring
     */
    public static function getWebVitalsScript(): string
    {
        return <<<JAVASCRIPT
        <script>
        // قياس Largest Contentful Paint
        new PerformanceObserver((entryList) => {
            const entries = entryList.getEntries();
            const lastEntry = entries[entries.length - 1];
            console.log('LCP:', lastEntry.renderTime || lastEntry.loadTime);
        }).observe({entryTypes: ['largest-contentful-paint']});

        // قياس First Input Delay
        new PerformanceObserver((entryList) => {
            const entries = entryList.getEntries();
            entries.forEach((entry) => {
                console.log('FID:', entry.processingDuration);
            });
        }).observe({entryTypes: ['first-input']});

        // قياس Cumulative Layout Shift
        let clsValue = 0;
        new PerformanceObserver((entryList) => {
            entryList.getEntries().forEach((entry) => {
                if (!entry.hadRecentInput) {
                    clsValue += entry.value;
                    console.log('CLS:', clsValue);
                }
            });
        }).observe({entryTypes: ['layout-shift']});
        </script>
        JAVASCRIPT;
    }

    /**
     */
    public static function registerServiceWorker(): string
    {
        return <<<JAVASCRIPT
        <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/js/sw.js')
                .then(reg => console.log('Service Worker registered'))
                .catch(err => console.error('Service Worker registration failed'));
        }
        </script>
        JAVASCRIPT;
    }
}
