<?php

namespace App\Services\Listing;

use App\Models\Listing\Listing;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class UpdateListingService
{
    public function execute(Listing $listing, array $data): Listing
    {
        return DB::transaction(function () use ($listing, $data) {

            $listing->update([
                'listing_type_id' => $data['listing_type_id'],
                'title' => $data['title'],
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'],
                'city_id' => $data['city_id'],
                'country_id' => $data['country_id'],
                'address' => $data['address'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'availability_status' => $data['availability_status'] ?? $listing->availability_status,
                'is_hidden' => $data['is_hidden'] ?? $listing->is_hidden,
                'hidden_reason' => $data['is_hidden'] ?? false ? ($data['hidden_reason'] ?? 'Hidden by admin.') : null,
                'moderation_comment' => $data['moderation_comment'] ?? ($data['is_hidden'] ?? false ? ($data['hidden_reason'] ?? 'Hidden by admin.') : null),
            ]);

            if (!empty($data['category_ids'])) {
                $listing->categories()->sync($data['category_ids']);
            }

            if (!empty($data['features'])) {
                $features = [];
                foreach ($data['features'] as $featureId) {
                    $features[$featureId] = ['value' => 'true'];
                }
                $listing->features()->sync($features);
            } else {
                $listing->features()->detach();
            }

            if (!empty($data['tags'])) {
                $listing->tags()->sync($data['tags']);
            } else {
                $listing->tags()->detach();
            }

            if (!empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $index => $image) {
                    if ($image instanceof UploadedFile && $image->isValid()) {
                        try {
                            $optimizedImage = ImageOptimizationService::optimizeUploadedImage(
                                $image,
                                1800,
                                1200,
                                2 * 1024 * 1024,
                                88
                            );

                            $listing->addMedia($optimizedImage)
                                ->toMediaCollection('images');

                            Log::info('Image uploaded successfully (update)', [
                                'index' => $index,
                                'name' => $optimizedImage->getClientOriginalName(),
                                'size' => $optimizedImage->getSize(),
                                'mime' => $optimizedImage->getMimeType(),
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to save image in Media Library (update)', [
                                'index' => $index,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    } else {
                        Log::warning('Item is not an UploadedFile object (update)', [
                            'index' => $index,
                            'type' => gettype($image),
                        ]);
                    }
                }
            }

            if (!empty($data['remove_images'])) {
                $deleted = $listing->media()
                    ->whereIn('id', $data['remove_images'])
                    ->delete();

                Log::info('Selected images deleted', [
                    'count' => $deleted,
                    'ids' => $data['remove_images'],
                ]);
            }

            $listing->refresh();

            event(new \App\Events\ListingUpdated($listing));

            return $listing;
        });
    }
}