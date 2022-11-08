<?php

namespace App\Repositories;

use App\Repositories\Contracts\ImageRepositoryContract;
use Illuminate\Database\Eloquent\Model;

class ImageRepository implements ImageRepositoryContract
{
    /**
     * call_user_func([$model, $methodName])->create(['path' => $image]);
     * $product = new Product();
     * $product->images()->create(['path' => $image]);
     *
     * @param  Model  $model -> Product obj
     * @param  string  $methodName -> images()
     * @param  array  $images
     *
     * @throws \Exception
     */
    public static function attach(Model $model, string $methodName, array $images = [])
    {
        if (! method_exists($model, $methodName)) {
            throw new \Exception($model::class." doesn't have {$methodName}");
        }

        if (! empty($images)) {
            foreach ($images as $image) {
                call_user_func([$model, $methodName])->create(['path' => $image]);
            }
        }
    }
}
