<?php

namespace App\Models\Listing;

use App\Traits\HasTranslations; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



/**
 * @property string $value
 */


class Category extends Model
{
    use HasTranslations; 

    protected $fillable = ['name', 'slug', 'parent_id'];

    protected $translatableFields = ['name'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function listings(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Listing\Listing::class, 'listing_category');
    }
}
