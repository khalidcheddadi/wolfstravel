<?php

namespace App\Models;

use App\Models\Listing\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image',
        'category_id', 'user_id', 'is_published', 'published_at', 'is_hidden', 'hidden_reason', 'moderation_comment'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'is_hidden' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('private')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(400)
                    ->height(300)
                    ->format('webp');

                $this->addMediaConversion('medium')
                    ->width(800)
                    ->height(600)
                    ->format('webp');

                $this->addMediaConversion('large')
                    ->width(1200)
                    ->height(800)
                    ->format('webp');
            });
    }

    public function getFeaturedImageUrl(string $conversion = 'medium', int $expiry = 30): ?string
    {
        $media = $this->getFirstMedia('images');

        if ($media) {
            return $this->getMediaSignedUrl($media, $conversion, $expiry);
        }

        if (!empty($this->featured_image)) {
            return asset('storage/' . $this->featured_image);
        }

        return null;
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeVisible($query)
    {
        return $query->where('is_published', true)
            ->where('is_hidden', false);
    }

    public function scopeHidden($query)
    {
        return $query->where('is_hidden', true);
    }

    public function scopeLatestPublished($query)
    {
        return $query->published()->latest('published_at');
    }
}
