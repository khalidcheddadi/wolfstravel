<?php

namespace App\Models\Location;

use App\Traits\HasTranslations; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @property string $value
 */


class Region extends Model
{
    use HasTranslations; 

    protected $fillable = ['name', 'slug', 'city_id'];

    protected $translatableFields = ['name'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
