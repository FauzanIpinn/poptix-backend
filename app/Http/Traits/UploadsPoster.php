<?php

namespace App\Http\Traits;

use Illuminate\Http\UploadedFile;

trait UploadsPoster
{
    protected function uploadPoster(UploadedFile $file): string
    {
        $result = cloudinary()->uploadApi()->upload($file->getRealPath(), [
            'folder' => 'poptix/posters',
        ]);

        return $result['secure_url'];
    }

    protected function deletePoster(?string $url): void
    {
        if (! $url) {
            return;
        }

        // Example URL: https://res.cloudinary.com/cloud/image/upload/v1234/poptix/posters/xyz.jpg
        // We want to extract 'poptix/posters/xyz'
        if (preg_match('/upload\/(?:v\d+\/)?(.+?)\.[a-zA-Z0-9]+$/', $url, $matches)) {
            $publicId = $matches[1];
            try {
                cloudinary()->uploadApi()->destroy($publicId);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Cloudinary delete failed', [
                    'public_id' => $publicId,
                    'error'     => $e->getMessage()
                ]);
            }
        }
    }
}