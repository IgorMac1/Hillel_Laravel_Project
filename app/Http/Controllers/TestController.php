<?php

namespace App\Http\Controllers;

use App\Services\Contracts\FileStorageServiceContract;

class TestController
{
    public function __construct(protected FileStorageServiceContract $contract)
    {
    }

    public function test()
    {
        dd($this->contract->generate());
    }
}
