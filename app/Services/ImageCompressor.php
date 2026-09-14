<?php

namespace App\Services;

use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;
use Throwable;

class ImageCompressor
{
    /**
     * Compress (and if needed downscale) an image file in-place until its size is <= $targetBytes.
     * Returns true if the file exists and is <= target after processing, false otherwise.
     */
    public static function compressToMaxBytes(string $absolutePath, int $targetBytes = 1024_000): bool
    {
        if (! is_file($absolutePath)) {
            return false;
        }

        // Early exit if already under target
        if (filesize($absolutePath) <= $targetBytes) {
            return true;
        }

        try {
            $image = Image::load($absolutePath);
        } catch (Throwable $e) {
            return false;
        }

        // Try progressively lowering quality
        $qualities = [85, 75, 65, 55, 45, 35, 25, 20, 15, 10];

        foreach ($qualities as $q) {
            try {
                Image::load($absolutePath)
                    ->quality($q)
                    ->save($absolutePath);
            } catch (Throwable $e) {
                // If quality change fails for this format, continue
            }

            clearstatcache(true, $absolutePath);
            if (filesize($absolutePath) <= $targetBytes) {
                return true;
            }
        }

        // If still too large, gradually downscale based on ratio between current and target size
        // Limit to a few iterations to avoid excessive processing
        $maxDownscaleIterations = 4;
        for ($i = 0; $i < $maxDownscaleIterations; $i++) {
            try {
                $img = Image::load($absolutePath);
                $width = $img->getWidth();
                $height = $img->getHeight();

                // Calculate scale factor using square root of byte ratio (heuristic)
                $currentSize = filesize($absolutePath);
                if ($currentSize <= 0) {
                    break;
                }
                $scale = sqrt($targetBytes / $currentSize);
                // Clamp scale to avoid tiny steps
                $scale = max(min($scale, 0.9), 0.5); // reduce between 10% and 50%

                $newW = max(320, (int) floor($width * $scale));
                $newH = max(320, (int) floor($height * $scale));

                // Maintain aspect ratio with contain fit
                $img->fit(Fit::Contain, $newW, $newH)
                    ->quality(65)
                    ->save($absolutePath);
            } catch (Throwable $e) {
                break;
            }

            clearstatcache(true, $absolutePath);
            if (filesize($absolutePath) <= $targetBytes) {
                return true;
            }
        }

        // Final attempt: another strong compression pass
        try {
            Image::load($absolutePath)->quality(35)->save($absolutePath);
        } catch (Throwable $e) {
            // ignore
        }

        clearstatcache(true, $absolutePath);

        return filesize($absolutePath) <= $targetBytes;
    }
}
