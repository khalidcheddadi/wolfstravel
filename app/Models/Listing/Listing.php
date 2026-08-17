<?php

namespace App\Models\Listing;

use App\Models\Business\Business;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\User;
use App\Models\Review\Review;
use App\Helpers\Sanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use App\Traits\HasTranslations;


/**
 * @property int $owner_id
 * @property string $value
 * @property-read \App\Models\User $owner   
 */

class Listing extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasTranslations;

    protected $fillable = [
        'uuid', 'business_id', 'listing_type_id', 'city_id', 'country_id',
        'slug', 'title', 'short_description', 'description',
        'address', 'latitude', 'longitude',
        'average_rating', 'total_reviews', 'views', 'favorites_count',
        'status', 'published_at', 'availability_status', 'is_hidden', 'hidden_reason', 'moderation_comment'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'average_rating' => 'float',
        'published_at' => 'datetime',
        'availability_status' => 'string',
        'is_hidden' => 'boolean',
    ];

    protected $translatableFields = ['title', 'short_description', 'description'];


    public function setShortDescriptionAttribute($value)
    {
        $this->attributes['short_description'] = Sanitizer::sanitizeHtml($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = Sanitizer::sanitizeHtml($value);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = Sanitizer::sanitizeText($value);
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = Sanitizer::sanitizeText($value);
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->useDisk('private')
             ->registerMediaConversions(function (Media $media) {
                 $this->addMediaConversion('thumb')
                      ->width(400)->height(300)
                      ->format('webp');
                 $this->addMediaConversion('medium')
                      ->width(800)->height(600)
                      ->format('webp');
                 $this->addMediaConversion('large')
                      ->width(1200)->height(800)
                      ->format('webp');
             });
    }

    public function getSignedImageUrl(string $conversion = 'medium', int $expiry = 30): ?string
    {
        $media = $this->getFirstMedia('images');
        if (!$media) {
            return null;
        }

        if ($conversion !== 'original') {
            try {
                $media->refresh();
                if (!$media->hasGeneratedConversion($conversion)) {
                    Log::info('No conversion available, original image will be used', [
                        'media_id' => $media->id,
                        'conversion' => $conversion,
                    ]);
                    $conversion = 'original';
                }
            } catch (\Exception $e) {
                Log::warning(' Failed to check conversion, using original', [
                    'media_id' => $media->id,
                    'error' => $e->getMessage(),
                ]);
                $conversion = 'original';
            }
        }

        return URL::temporarySignedRoute(
            'media.download',
            now()->addMinutes($expiry),
            [
                'media' => $media->id,
                'conversion' => $conversion,
            ]
        );
    }

    public function getMediaSignedUrl(Media $media, string $conversion = 'medium', int $expiry = 30): ?string
    {
        if ($conversion !== 'original') {
            try {
                if (!$media->hasGeneratedConversion($conversion)) {
                    $conversion = 'original';
                }
            } catch (\Exception $e) {
                $conversion = 'original';
            }
        }

        return URL::temporarySignedRoute(
            'media.download',
            now()->addMinutes($expiry),
            [
                'media' => $media->id,
                'conversion' => $conversion,
            ]
        );
    }


    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ListingType::class, 'listing_type_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'listing_category');
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(
            ListingFeature::class,
            'listing_feature_values',
            'listing_id',
            'feature_id'
        )->withPivot('value')
         ->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            ListingTag::class,
            'listing_tag',
            'listing_id',
            'tag_id'
        )->withTimestamps();
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }


    public function updateAverageRating()
    {
        $avg = $this->reviews()->where('status', 'approved')->avg('rating');
        $count = $this->reviews()->where('status', 'approved')->count();

        $this->update([
            'average_rating' => round($avg ?? 0, 1),
            'total_reviews' => $count,
        ]);
    }

    public function publicAvailabilityState(): ?string
    {
        $state = $this->availability_status;

        if (!in_array($state, ['open', 'closed'], true)) {
            return null;
        }

        return $state;
    }

    public function availabilityBadgeLabel(): ?string
    {
        return match ($this->publicAvailabilityState()) {
            'open' => __('messages.search_open_now'),
            'closed' => __('messages.search_closed_now'),
            default => null,
        };
    }

    public function scopeVisible($query)
    {
        return $query->where('status', 'published')
            ->where('is_hidden', false);
    }

    public function scopeHidden($query)
    {
        return $query->where('is_hidden', true);
    }
}
