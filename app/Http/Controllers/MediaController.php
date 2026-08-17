<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MediaController extends Controller
{
    public function download(Request $request, Media $media, $conversion = null)
    {
        $model = $media->model;

        if ($model instanceof \App\Models\Listing\Listing) {
            if ($model->status === 'published') {
            } else {
                $user = Auth::user();
                $isOwner = $user && $user->id === $model->business->owner_id;
                $isAdmin = $user && $user->hasRole('admin');

                if (!$isOwner && !$isAdmin) {
                    abort(403, 'You are not authorized to download this file.');
                }
            }
        }

        try {
            if ($conversion && $conversion !== 'original') {
                $path = $media->getPath($conversion);

                if (!file_exists($path)) {
                    Log::info('Attempting to generate conversion on demand', [
                        'media_id' => $media->id,
                        'conversion' => $conversion,
                    ]);

                    $media->refresh();

                    $path = $media->getPath($conversion);

                    if (!file_exists($path)) {
                        Log::warning('Conversion not found, using original image', [
                            'media_id' => $media->id,
                            'conversion' => $conversion,
                        ]);
                        $path = $media->getPath();
                        $conversion = 'original';
                    }
                }
            } else {
                $path = $media->getPath();
            }

            if (!file_exists($path)) {
                Log::error('File not found', [
                    'media_id' => $media->id,
                    'conversion' => $conversion,
                    'path' => $path,
                ]);
                abort(404, 'File not found.');
            }

            $mimeType = mime_content_type($path) ?: 'application/octet-stream';

            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=86400',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to download file', [
                'media_id' => $media->id,
                'conversion' => $conversion,
                'error' => $e->getMessage(),
            ]);
            abort(404, 'Unable to find the file: ' . $e->getMessage());
        }
    }
}