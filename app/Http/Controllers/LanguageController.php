<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        $allowed = ['en', 'es', 'fr', 'ar', 'de'];
        if (!in_array($locale, $allowed)) {
            $locale = 'en';
        }

        session()->put('locale', $locale);
        session()->save();
        App::setLocale($locale);


        $currentRoute = Route::current();

        if (!$currentRoute) {
            return redirect()->route("{$locale}.home");
        }

        $routeName = $currentRoute->getName();

        if (!$routeName || !str_contains($routeName, '.')) {
            return redirect()->route("{$locale}.home");
        }


        $parts = explode('.', $routeName);
        $baseRouteName = implode('.', array_slice($parts, 1));

        if (!Route::has("{$locale}.{$baseRouteName}")) {
            return redirect()->route("{$locale}.home");
        }

        $parameters = $currentRoute->parameters();

        return redirect()->route("{$locale}.{$baseRouteName}", $parameters);
    }
}
