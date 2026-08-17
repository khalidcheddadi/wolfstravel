<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BusinessOwner\ListingController as BusinessListingController;
use App\Http\Controllers\BusinessOwner\DashboardController;
use App\Http\Controllers\BusinessOwner\BusinessController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\SiteReviewController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ListingController as PublicListingController;
use App\Http\Controllers\Public\SearchController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\CityController;
use App\Http\Controllers\Public\FavoriteController;
use App\Http\Controllers\Public\ReviewController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\PostImportController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Ajax\CitySearchController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Public\PrivacyController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\PostController;

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

Route::middleware('auth')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
});
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/privacy', [PrivacyController::class, 'index'])->name('privacy');

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/listings', [AdminListingController::class, 'index'])->name('listings.index');
        Route::get('/listings/{listing}/edit', [AdminListingController::class, 'edit'])->name('listings.edit');
        Route::put('/listings/{listing}', [AdminListingController::class, 'update'])->name('listings.update');
        Route::get('/listings/{listing}/review', [AdminListingController::class, 'review'])->name('listings.review');
        Route::post('/listings/{listing}/approve', [AdminListingController::class, 'approve'])->name('listings.approve');
        Route::post('/listings/{listing}/reject', [AdminListingController::class, 'reject'])->name('listings.reject');

        Route::get('/listings/{listing}/rate', [AdminListingController::class, 'rateForm'])->name('listings.rate.form');
        Route::post('/listings/{listing}/rate', [AdminListingController::class, 'rateStore'])->name('listings.rate.store');

        Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
        Route::get('/posts/{post}/edit', [AdminPostController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}', [AdminPostController::class, 'update'])->name('posts.update');

        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/posts/import', [PostImportController::class, 'index'])->name('posts.import.index');
        Route::post('/posts/import', [PostImportController::class, 'import'])->name('posts.import.run');
    });

Route::middleware(['auth', 'verified', 'business_owner'])
    ->prefix('business-owner')
    ->name('business-owner.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/business/create', [BusinessController::class, 'create'])->name('business.create');
        Route::post('/business', [BusinessController::class, 'store'])->name('business.store');
        Route::get('/business/edit', [BusinessController::class, 'edit'])->name('business.edit');
        Route::put('/business', [BusinessController::class, 'update'])->name('business.update');

        Route::post('/listings/{listing}/submit', [BusinessListingController::class, 'submit'])->name('listings.submit');
        Route::resource('listings', BusinessListingController::class)->except(['show']);

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');

        Route::get('/map-iframe', function () {
            return view('business-owner.map-iframe');
        })->name('map-iframe');
    });

Route::middleware(['auth', 'verified'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/reviews', [SiteReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews', [SiteReviewController::class, 'store'])->name('reviews.store');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/logout-all-devices', [ProfileController::class, 'logoutAllDevices'])
        ->name('profile.logout-all-devices');

    Route::post('/favorite/{listing}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    Route::post('/listing/{listing}/review', [ReviewController::class, 'store'])
        ->middleware('throttle:review')
        ->name('review.store');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('business_owner')) {
        return redirect()->route('business-owner.dashboard');
    } elseif ($user->hasRole('customer')) {
        return redirect()->route('customer.dashboard');
    } else {
        return redirect()->route('home')->with('info', 'You do not have permission to access the control panel.');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/media/{media}/download/{conversion?}', [MediaController::class, 'download'])
    ->middleware('signed')
    ->name('media.download');

Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::middleware(['auth', 'verified', 'throttle:20,1'])->prefix('ajax')->name('ajax.')->group(function () {
    Route::get('/cities/search', [CitySearchController::class, 'search'])->name('cities.search');
    Route::post('/countries', [LocationController::class, 'storeCountry'])->name('countries.store');
    Route::post('/cities', [LocationController::class, 'storeCity'])->name('cities.store');
    Route::get('/cities/{countryId}', [LocationController::class, 'getCities'])->name('cities.get');
});

// ─── Localized routes (canonical, returns 200 OK) ───
$locales = ['en', 'es', 'fr', 'ar', 'de'];

foreach ($locales as $locale) {
    Route::prefix($locale)
        ->name("{$locale}.")
        ->group(function () use ($locale) {

            Route::get('/', [HomeController::class, 'index'])->name('home');

            Route::get('/search', [SearchController::class, 'index'])
                ->middleware('throttle:search')
                ->name('search');

            Route::get('/contact', [ContactController::class, 'index'])->name('contact');

            Route::get('/listing/{slug}', [PublicListingController::class, 'show'])->name('listing.show');

            Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
            Route::get('/city/{slug}', [CityController::class, 'show'])->name('city.show');
            Route::get('/listings', [PublicListingController::class, 'index'])->name('listings.index');

            Route::get('/map-show-iframe', function () {
                return view('public.map-show-iframe');
            })->name('map-show-iframe');
        });
}

// ─── Root URL: serve content directly (no redirect) ───
Route::get('/', [HomeController::class, 'index'])->name('home.root');

// ─── SEO Fallback Redirects ───
// IMPORTANT: These use config('app.fallback_locale') instead of App::getLocale()
// so that Googlebot always receives a DETERMINISTIC, STABLE 301 redirect target.
// Previously these used App::getLocale() which varies based on session/Accept-Language
// headers, causing Google Search Console to flag them as "Redirect errors".

Route::get('/home', function () {
    $locale = config('app.fallback_locale', 'es');
    $query = request()->query();
    $queryString = http_build_query($query);
    $url = "/{$locale}" . ($queryString ? "?{$queryString}" : "");
    return redirect($url, 301);
})->name('fallback.home');

Route::get('/search', function () {
    $locale = config('app.fallback_locale', 'es');
    $query = request()->query();
    $queryString = http_build_query($query);
    $url = "/{$locale}/search" . ($queryString ? "?{$queryString}" : "");
    return redirect($url, 301);
})->name('fallback.search');

Route::get('/listing/{slug}', function ($slug) {
    $locale = config('app.fallback_locale', 'es');
    return redirect("/{$locale}/listing/{$slug}", 301);
})->name('fallback.listing.show');

Route::get('/category/{slug}', function ($slug) {
    $locale = config('app.fallback_locale', 'es');
    return redirect("/{$locale}/category/{$slug}", 301);
})->name('fallback.category.show');

Route::get('/city/{slug}', function ($slug) {
    $locale = config('app.fallback_locale', 'es');
    return redirect("/{$locale}/city/{$slug}", 301);
})->name('fallback.city.show');

Route::get('/listings', function () {
    $locale = config('app.fallback_locale', 'es');
    $query = request()->query();
    $queryString = http_build_query($query);
    $url = "/{$locale}/listings" . ($queryString ? "?{$queryString}" : "");
    return redirect($url, 301);
})->name('fallback.listings.index');

Route::get('/map-show-iframe', function () {
    $locale = config('app.fallback_locale', 'es');
    return redirect("/{$locale}/map-show-iframe", 301);
})->name('fallback.map-show-iframe');

require __DIR__ . '/auth.php';
