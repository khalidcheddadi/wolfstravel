<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ImageOptimizationService
{
    /**
     * Reduce an uploaded image automatically when it exceeds configured thresholds.
     * It compresses to browser-safe JPEG/WEBP/Png and scales dimensions down.
     *
     * @return UploadedFile
     */
    public static function optimizeUploadedImage(
        UploadedFile $file,
        ?int $maxWidth = 1800,
        ?int $maxHeight = 1200,
        int $maxBytes = 2 * 1024 * 1024,
        int $defaultQuality = 88
    ): UploadedFile {
        if (!extension_loaded('gd') || !function_exists('imagecreatefromstring')) {
            return $file;
        }

        if (!$file->isValid()) {
            return $file;
        }

        $realPath = $file->getPathname();

        if (!is_file($realPath) || !is_readable($realPath)) {
            return $file;
        }

        $mime = mime_content_type($realPath) ?: $file->getMimeType();

        if (!str_starts_with((string) $mime, 'image/')) {
            return $file;
        }

        $size = @filesize($realPath);
        $size = is_int($size) ? $size : 0;

        $dimensions = @getimagesize($realPath);
        if (!$dimensions || !isset($dimensions[0], $dimensions[1])) {
            return $file;
        }

        $width = (int) $dimensions[0];
        $height = (int) $dimensions[1];

        $needsOptimization = ($size > $maxBytes) || ($width > $maxWidth) || ($height > $maxHeight);

        if (!$needsOptimization) {
            return $file;
        }

        $sourceImage = self::createImageFromFile($realPath, $mime);

        if (!$sourceImage) {
            return $file;
        }

        $targetWidth = $width;
        $targetHeight = $height;
        $targetQuality = $defaultQuality;

        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
            $targetWidth = max(1, (int) round($width * $ratio));
            $targetHeight = max(1, (int) round($height * $ratio));
        }

        // If the file is still oversized after reading the dimensions, reduce quality progressively.
        $attempt = 0;
        $optimizedPath = $realPath;

        do {
            $attempt++;

            $resampled = imagecreatetruecolor($targetWidth, $targetHeight);

            if ($mime === 'image/png') {
                imagealphablending($resampled, false);
                imagesavealpha($resampled, true);
                $transparent = imagecolorallocatealpha($resampled, 255, 255, 255, 127);
                imagefilledrectangle($resampled, 0, 0, $targetWidth, $targetHeight, $transparent);
            }

            imagecopyresampled(
                $resampled,
                $sourceImage,
                0,
                0,
                0,
                0,
                $targetWidth,
                $targetHeight,
                $width,
                $height
            );

            $tmpFile = tempnam(sys_get_temp_dir(), 'trav_opt_');
            $tmpFileMeta = $tmpFile . '.jpg';
            @unlink($tmpFile);

            $storeMime = 'image/jpeg';
            $extension = 'jpg';

            if ($mime === 'image/webp' && function_exists('imagewebp')) {
                $storeMime = 'image/webp';
                $extension = 'webp';
                imagewebp($resampled, $tmpFileMeta, $targetQuality);
            } elseif ($mime === 'image/png' && function_exists('imagepng')) {
                $storeMime = 'image/png';
                $extension = 'png';
                imagepng($resampled, $tmpFileMeta, max(0, min(9, (int) round($targetQuality / 10))));
            } else {
                imagejpeg($resampled, $tmpFileMeta, $targetQuality);
            }

            imagedestroy($resampled);

            $optimizedSize = @filesize($tmpFileMeta);
            $optimizedSize = is_int($optimizedSize) ? $optimizedSize : 0;

            if ($optimizedSize > 0 && $optimizedSize <= $maxBytes) {
                $optimizedPath = $tmpFileMeta;
                break;
            }

            $targetQuality = max(35, $targetQuality - 12);
            $targetWidth = max(1, (int) round($targetWidth * 0.88));
            $targetHeight = max(1, (int) round($targetHeight * 0.88));

            @unlink($tmpFileMeta);

            if ($attempt >= 5) {
                $optimizedPath = $tmpFileMeta;
                break;
            }
        } while (true);

        imagedestroy($sourceImage);

        $newFileName = $file->getClientOriginalName();
        $newFileName = pathinfo($newFileName, PATHINFO_FILENAME) . '.' . $extension;

        $uploaded = new UploadedFile(
            $optimizedPath,
            $newFileName,
            $storeMime,
            null,
            true
        );

        Log::info('Image optimized automatically', [
            'original' => $file->getClientOriginalName(),
            'optimized' => $newFileName,
            'original_size' => $size,
            'optimized_size' => @filesize($optimizedPath),
            'target_width' => $targetWidth,
            'target_height' => $targetHeight,
            'target_quality' => $targetQuality,
        ]);

        return $uploaded;
    }

    private static function createImageFromFile(string $path, string $mime)
    {
        if ($mime === 'image/jpeg') {
            return imagecreatefromjpeg($path);
        }

        if ($mime === 'image/png') {
            return imagecreatefrompng($path);
        }

        if ($mime === 'image/webp') {
            if (function_exists('imagecreatefromwebp')) {
                return imagecreatefromwebp($path);
            }
        }

        return null;
    }
}
