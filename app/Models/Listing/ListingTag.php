<?php

namespace App\Models\Listing;

use App\Traits\HasTranslations; 
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $value
 */

class ListingTag extends Model
{
    use HasTranslations; 

    protected $fillable = ['name', 'slug'];

    protected $translatableFields = ['name'];
}
