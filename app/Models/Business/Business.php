<?php

namespace App\Models\Business;

use App\Models\User;  
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Listing\Listing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'owner_id', 'business_name', 'legal_name', 'slug',
        'business_type_id', 'description', 'email', 'phone', 'website',
        'logo', 'cover', 'country_id', 'city_id', 'address',
        'latitude', 'longitude', 'verified', 'status'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'verified' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }
}
