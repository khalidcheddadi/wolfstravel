<?php

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;

trait HasTranslations
{
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     */
    public function translate(string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?? App::getLocale();
        $defaultLocale = config('app.fallback_locale', 'en');

        if ($locale === $defaultLocale) {
            return $this->{$field};
        }

        $translation = $this->translations()
            ->where('field', $field)
            ->where('locale', $locale)
            ->first();

        if ($translation) {
            return $translation->value;
        }

        return $this->{$field};
    }

    public function __call($method, $parameters)
    {
        return parent::__call($method, $parameters);
    }
}
