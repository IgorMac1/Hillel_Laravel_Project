<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoriesProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory(4)->create()->each(function ($category) {
            Product::factory(rand(2, 4))->make()->each(function ($product) use ($category) {
                $category->products()->create($product->attributesToArray());
            });
        });
    }
}
