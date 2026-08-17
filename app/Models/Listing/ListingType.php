<?php

namespace App\Models\Listing;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $value
 */

class ListingType extends Model
{
    use HasTranslations; 

    protected $fillable = ['name', 'slug', 'icon'];

    protected $translatableFields = ['name'];
}
