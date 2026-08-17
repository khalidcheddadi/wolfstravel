<?php

namespace App\Models\Location;

use App\Traits\HasTranslations; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $value
 */

class Country extends Model
{
    use HasTranslations; 

    protected $fillable = ['name', 'code', 'slug'];

    protected $translatableFields = ['name'];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
