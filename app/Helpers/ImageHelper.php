<?php

namespace App\Helpers;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Upload and resize image
     */
    public static function uploadImage($file, $folder = 'products', $width = 800, $height = 800)
    {
        if (!$file) {
            return null;
        }

        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $filename;

        // Create image instance and resize
        $image = Image::read($file);
        
        // Resize maintaining aspect ratio
        $image->scale(width: $width, height: $height);

        // Save to storage
        Storage::disk('public')->put($path, (string) $image->encode());

        return $path;
    }

    /**
     * Upload multiple images
     */
    public static function uploadMultipleImages($files, $folder = 'products', $width = 800, $height = 800)
    {
        $uploadedImages = [];

        foreach ($files as $file) {
            $uploadedImages[] = self::uploadImage($file, $folder, $width, $height);
        }

        return $uploadedImages;
    }

    /**
     * Delete image from storage
     */
    public static function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return true;
        }
        return false;
    }

    /**
     * Delete multiple images
     */
    public static function deleteMultipleImages($paths)
    {
        foreach ($paths as $path) {
            self::deleteImage($path);
        }
    }
}