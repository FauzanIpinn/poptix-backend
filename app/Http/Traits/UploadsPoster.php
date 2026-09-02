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
}