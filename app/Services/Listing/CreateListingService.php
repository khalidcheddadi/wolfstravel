<?php

namespace App\Services\Listing;

use App\Models\Listing\Listing;
use App\Models\Business\Business;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class CreateListingService
{
    public function execute(Business $business, array $data): Listing
    {
        return DB::transaction(function () use ($business, $data) {

            $listing = Listing::create([
                'uuid' => Str::uuid(),
                'business_id' => $business->id,
                'listing_type_id' => $data['listing_type_id'],
                'city_id' => $data['city_id'],
                'country_id' => $data['country_id'],
                'slug' => Str::slug($data['title']) . '-' . Str::random(6),
                'title' => $data['title'],
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'],
                'address' => $data['address'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'availability_status' => $data['availability_status'] ?? null,
                'status' => 'draft',
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
            }

            if (!empty($data['tags'])) {
                $listing->tags()->sync($data['tags']);
            }

            if (!empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $index => $image) {
                    if ($image instanceof UploadedFile) {
                        if ($image->isValid()) {
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

                                Log::info('Image uploaded successfully', [
                                    'index' => $index,
                                    'name' => $optimizedImage->getClientOriginalName(),
                                    'size' => $optimizedImage->getSize(),
                                    'mime' => $optimizedImage->getMimeType(),
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Failed to save image in Media Library', [
                                    'index' => $index,
                                    'error' => $e->getMessage(),
                                ]);
                            }
                        } else {
                            Log::error('Image upload failed - file is invalid', [
                                'index' => $index,
                                'error_code' => $image->getError(),
                                'error_message' => $this->getUploadErrorMessage($image->getError()),
                            ]);
                        }
                    } else {
                        Log::warning('Item is not an UploadedFile object', [
                            'index' => $index,
                            'type' => gettype($image),
                        ]);
                    }
                }

                $listing->refresh();
            } else {
                Log::info('No images attached to the listing');
            }

            event(new \App\Events\ListingCreated($listing));

            return $listing;
        });
    }

    private function getUploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE => 'File size exceeds the limit set in php.ini (upload_max_filesize)',
            UPLOAD_ERR_FORM_SIZE => 'File size exceeds the limit set in the form (MAX_FILE_SIZE)',
            UPLOAD_ERR_PARTIAL => 'The file was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was selected for upload',
            UPLOAD_ERR_NO_TMP_DIR => 'Temporary folder is missing on the server',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by a PHP extension',
            default => 'Unknown file upload error (code: ' . $errorCode . ')',
        };
    }
}