<?php

namespace App\Services\Contracts;

use Illuminate\Http\UploadedFile;

interface FileStorageServiceContract
{
    /**
     * @param  UploadedFile|string  $file
     * @return string - public/images/image_name.png
     */
    public static function upload(UploadedFile|string $file): string;

    /**
     * @param  string  $file - public/images/image_name.png
     * @return mixed
     */
    public static function remove(string $file);
}
