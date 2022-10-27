<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ImageRepositoryContract
{
    public static function attach(Model $model, string $methodName, array $images = []);
}
