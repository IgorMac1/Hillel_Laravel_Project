<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::all()->take(4);
        $products = Product::all()->where('in_stock', '>', 0)->take(6);

        return view('home', compact('products', 'categories'));
    }
}
