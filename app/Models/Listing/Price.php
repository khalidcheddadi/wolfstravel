<?php

namespace App\Models\Listing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    /**
     */
    protected $table = 'listing_prices';

    protected $fillable = [
        'listing_id',
        'title',
        'price',
        'currency',
        'price_type',
        'min_persons',
        'max_persons',
        'valid_from',
        'valid_to',
    ];

    protected $casts = [
        'price' => 'float',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}
