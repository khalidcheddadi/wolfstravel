<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSeoMeta
{
    public function getMetaTitleAttribute()
    {
        return $this->seo_meta['title'] ?? $this->title ?? config('app.name');
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->seo_meta['description'] ?? Str::limit(strip_tags($this->description ?? ''), 160);
    }

    public function getMetaImageAttribute()
    {
        return $this->seo_meta['image'] ?? $this->getFirstMediaUrl('images', 'medium');
    }
}
