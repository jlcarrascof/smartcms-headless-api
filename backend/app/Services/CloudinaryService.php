<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    /**
     * Upload an image to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string The URL of the uploaded image
     */
    public function uploadImage(UploadedFile $file, string $folder = 'smartcms'): string
    {
        $uploadedFileUrl = cloudinary()->uploadApi()->upload($file->getRealPath(), [
            'folder' => $folder,
        ])['secure_url'];

        return $uploadedFileUrl;
    }

    /**
     * Delete an image from Cloudinary using its URL
     *
     * @param string $url
     * @return bool
     */
    public function deleteImage(?string $url): bool
    {
        if (!$url) {
            return false;
        }

        $publicId = $this->getPublicIdFromUrl($url);

        if ($publicId) {
            cloudinary()->uploadApi()->destroy($publicId);
            return true;
        }

        return false;
    }

    /**
     * Extract the public ID from a Cloudinary secure URL
     *
     * @param string $url
     * @return string|null
     */
    private function getPublicIdFromUrl(string $url): ?string
    {
        // Example URL: https://res.cloudinary.com/demo/image/upload/v1312461204/folder/sample.jpg
        $parts = explode('/upload/', $url);
        
        if (count($parts) < 2) {
            return null;
        }
        
        $path = $parts[1];
        $pathParts = explode('/', $path);
        
        // Remove version if exists (e.g. v1312461204)
        if (preg_match('/^v\d+$/', $pathParts[0])) {
            array_shift($pathParts);
        }
        
        $publicIdWithExtension = implode('/', $pathParts);
        $pathInfo = pathinfo($publicIdWithExtension);
        
        return $pathInfo['dirname'] === '.' 
            ? $pathInfo['filename'] 
            : $pathInfo['dirname'] . '/' . $pathInfo['filename'];
    }
}
