<?php

namespace App\Http\Requests\BusinessOwner;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Sanitizer;

class UpdateListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('category_ids_json')) {
            $categoryIds = json_decode($this->input('category_ids_json'), true);
            if (is_array($categoryIds)) {
                $this->merge(['category_ids' => $categoryIds]);
            }
        }

        if ($this->has('features')) {
            $featuresInput = $this->input('features');
            if (is_array($featuresInput) && isset($featuresInput[0]) && is_string($featuresInput[0])) {
                $decoded = json_decode($featuresInput[0], true);
                if (is_array($decoded)) {
                    $this->merge(['features' => $decoded]);
                }
            }
        }

        $this->merge([
            'title' => Sanitizer::sanitizeText($this->title),
            'short_description' => Sanitizer::sanitizeHtml($this->short_description),
            'description' => Sanitizer::sanitizeHtml($this->description),
            'address' => Sanitizer::sanitizeText($this->address),
        ]);
    }

    public function rules(): array
    {
        return [
            'listing_type_id' => 'required|exists:listing_types,id',

            'title' => 'required|string|max:255|not_regex:/<script.*?>/i',
            'short_description' => 'nullable|string|max:500|not_regex:/<script.*?>/i',
            'description' => 'required|string|not_regex:/<script.*?>/i',

            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'country_id' => 'required|exists:countries,id',
            'address' => 'nullable|string|max:500|not_regex:/<script.*?>/i',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images' => 'nullable|array|max:10',
            'images.*' => [
                'required',
                'image',
                'mimes:jpeg,png,webp',
                'max:5120',
                function ($attribute, $value, $fail) {
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

                    if (!in_array($value->getMimeType(), $allowedMimes)) {
                        $fail('File type not allowed.');
                        return;
                    }

                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $value->getRealPath());
                    finfo_close($finfo);

                    if (!in_array($mime, $allowedMimes)) {
                        $fail('The file is corrupted or invalid.');
                    }
                },
            ],

            'features' => 'nullable|array',
            'features.*' => 'exists:listing_features,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:listing_tags,id',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer|exists:media,id',
            'is_hidden' => 'nullable|boolean',
            'hidden_reason' => 'nullable|string|max:500',
            'moderation_comment' => 'nullable|string|max:1000',
            'availability_status' => 'nullable|in:open,closed',
        ];
    }

    public function messages(): array
    {
        return [
            'category_ids.required' => 'You must select at least one category.',
            'images.*.max' => 'Image size must not exceed 5 MB.',
            'title.not_regex' => 'The title contains disallowed text.',
            'description.not_regex' => 'The description contains disallowed text.',
        ];
    }
}