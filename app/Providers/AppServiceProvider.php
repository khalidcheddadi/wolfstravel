<?php

namespace App\Providers;

use App\Models\Listing\Listing;
use App\Models\Review\Review;
use App\Models\Business\Business;
use App\Models\User;
use App\Policies\ListingPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\BusinessPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Listing::class => ListingPolicy::class,
        Review::class  => ReviewPolicy::class,
        Business::class => BusinessPolicy::class,
        User::class    => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\View::composer(['layouts.public', 'partials.header', 'public.sections.footer'], function ($view) {
            $cities = \Illuminate\Support\Facades\Cache::remember('all_cities_composer', 3600, function () {
                return \App\Models\Location\City::orderBy('name')->get();
            });
            $categories = \Illuminate\Support\Facades\Cache::remember('all_categories_composer', 3600, function () {
                return \App\Models\Listing\Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
            });
            $view->with('cities', $cities)->with('categories', $categories);
        });


        Gate::define('admin', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('business-owner', function (User $user) {
            return $user->hasRole('business_owner');
        });

        Gate::define('moderator', function (User $user) {
            return $user->hasRole('moderator');
        });

        Gate::define('customer', function (User $user) {
            return $user->hasRole('customer');
        });

        Gate::define('publish-listing', function (User $user, Listing $listing) {
            return $user->hasRole('admin') || $user->id === $listing->business->owner_id;
        });

        Gate::define('delete-any-review', function (User $user) {
            return $user->hasRole('admin');
        });
    }
}