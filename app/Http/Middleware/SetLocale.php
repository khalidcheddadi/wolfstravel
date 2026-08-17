<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locales = ['en', 'es', 'fr', 'ar', 'de'];

        // 1. Prioritize the URL segment (e.g. /es/city/madrid)
        $segment = $request->segment(1);
        if ($segment && in_array($segment, $locales)) {
            App::setLocale($segment);
            $request->session()->put('locale', $segment);
            return $next($request);
        }

        // 2. Fall back to session locale
        if ($locale = $request->session()->get('locale')) {
            App::setLocale($locale);
            return $next($request);
        }

        // 3. Fall back to browser preferred language
        $browserLocale = $request->getPreferredLanguage($locales);
        $defaultLocale = ($browserLocale && in_array($browserLocale, $locales))
            ? $browserLocale
            : 'es';

        App::setLocale($defaultLocale);
        $request->session()->put('locale', $defaultLocale);

        return $next($request);
    }
}
