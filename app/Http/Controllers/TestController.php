<?php

namespace App\Http\Controllers;

use App\Services\Contracts\UserInfoContract;

class TestController
{
    public function __construct(protected UserInfoContract $contract)
    {

    }


    public function test()
        {
            dd($this->contract->generate());
        }
}
