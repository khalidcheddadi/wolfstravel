<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Listing\Listing;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListingPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->hasRole(['business_owner', 'admin']);
    }

    public function update(User $user, Listing $listing): bool
    {
        return $user->id === $listing->business->owner_id || $user->hasRole('admin');
    }

    public function delete(User $user, Listing $listing): bool
    {
        return $user->id === $listing->business->owner_id || $user->hasRole('admin');
    }

    public function publish(User $user, Listing $listing): bool
    {
        return $user->hasRole('admin');
    }

    public function rate(User $user, Listing $listing): bool
    {
        return $user->hasRole('admin');
    }
}
