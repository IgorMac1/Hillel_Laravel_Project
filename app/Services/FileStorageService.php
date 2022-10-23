<?php

namespace App\Services;

use App\Services\Contract\FileStorageServiceContract;
use GuzzleHttp\Psr7\UploadedFile;

class FileStorageService implements FileStorageServiceContract
{
    public static function upload(UploadedFile|string $file): string
    {
        // TODO: Implement upload() method.
    }

    public static function remove(string $string)
    {
        // TODO: Implement remove() method.
    }
}
