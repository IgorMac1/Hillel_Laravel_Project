<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\FileStorageService;

class ProductObserver
{
    public function updated(Product $product)
    {
        $updatedQuantity = $product->getOriginal('in_stock') <= 0 && $product->getOriginal('in_stock') < $product->in_stock;
        $updatedPrice = $product->getOriginal('end_price') > $product->end_price;
        $updatedDiscount = $product->getOriginal('discount') < $product->discount;

        if ($updatedDiscount || $updatedPrice || $updatedQuantity) {
            $product->followers()->get()->each->notify(new ProductUpdateNotification(
                $product,
                $updatedPrice,
                $updatedQuantity,
                $updatedDiscount
            ));
        }
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        if ($product->images()->count() > 0) {
            $product->images->each->delete();
        }

        FileStorageService::remove($product->thumbnail);
    }
}
