<?php

namespace App\Services\Contract;

use GuzzleHttp\Psr7\UploadedFile;

interface FileStorageServiceContract
{
    /**
     * @param UploadedFile|string $file
     * @return string - public/images/image_name.png
     */
    public static function upload(UploadedFile|string $file): string;

    /**
     * @param string $string - public/images/image_name.png
     * @return mixed
     */
    public static function remove(string $string);
}
