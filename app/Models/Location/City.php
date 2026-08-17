<?php

namespace App\Models\Location;

use App\Traits\HasTranslations; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $value
 */

class City extends Model
{
    use HasTranslations; 

    protected $fillable = ['name', 'slug', 'country_id', 'latitude', 'longitude'];

    protected $translatableFields = ['name'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    public function listings(): HasMany
    {
        return $this->hasMany(\App\Models\Listing\Listing::class, 'city_id');
    }
}
