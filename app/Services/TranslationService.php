<?php

namespace App\Services;

use App\Models\Translation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected string $apiUrl;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.libretranslate.url', 'https://libretranslate.com/translate');
        $this->apiKey = config('services.libretranslate.api_key'); 
    }

    /**
     */
    public function translateText(string $text, string $targetLocale, string $sourceLocale = 'en'): ?string
    {
        if (empty(trim($text)) || $targetLocale === $sourceLocale) {
            return $text;
        }

        try {
            $payload = [
                'q' => $text,
                'source' => $sourceLocale,
                'target' => $targetLocale,
                'format' => 'text',
            ];

            if ($this->apiKey) {
                $payload['api_key'] = $this->apiKey;
            }

            $response = Http::timeout(30)->post($this->apiUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['translatedText'] ?? null;
            }

            Log::error('LibreTranslate API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Translation Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     */
    public function translateModel($model, string $targetLocale, string $sourceLocale = 'en'): void
    {
        $fields = $model->translatableFields ?? ['title', 'short_description', 'description'];

        foreach ($fields as $field) {
            if (empty($model->{$field})) {
                continue;
            }

            $existing = Translation::where([
                'translatable_type' => get_class($model),
                'translatable_id' => $model->id,
                'field' => $field,
                'locale' => $targetLocale,
            ])->first();

            if ($existing && !$existing->is_automatic) {
                continue; 
            }

            $translatedText = $this->translateText($model->{$field}, $targetLocale, $sourceLocale);

            if ($translatedText) {
                Translation::updateOrCreate(
                    [
                        'translatable_type' => get_class($model),
                        'translatable_id' => $model->id,
                        'field' => $field,
                        'locale' => $targetLocale,
                    ],
                    [
                        'value' => $translatedText,
                        'is_automatic' => true,
                    ]
                );
            }
        }
    }

    /**
     */
    public function translateCollection($models, string $targetLocale, string $sourceLocale = 'en'): void
    {
        foreach ($models as $model) {
            $this->translateModel($model, $targetLocale, $sourceLocale);
        }
    }
}
