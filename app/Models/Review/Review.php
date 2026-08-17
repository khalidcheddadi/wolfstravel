<?php

namespace App\Models\Review;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read \App\Models\Listing\Listing $listing
 * @property-read \App\Models\User $user
 */
class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'listing_id',
        'user_id',
        'rating',
        'title',
        'body',
        'status'
    ];

    public function listing()
    {
        return $this->belongsTo(\App\Models\Listing\Listing::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
