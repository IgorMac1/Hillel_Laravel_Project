<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart/index');
    }

    public function add(Request $request, Product $product)
    {
        Cart::instance('cart')->add(
            $product->id,
            $product->title,
            $request->product_count,
            $product->endPrice
        )->associate(Product::class);

        notify()->success("Product was added to the cart", position: "topRight");

        return redirect()->back();
    }

    public function remove(Request $request)
    {
        Cart::instance('cart')->remove($request->rowId);

        notify()->success("Product was removed", position: "topRight");

        return redirect()->back();
    }

    public function countUpdate(Request $request, Product $product)
    {
        if ($product->in_stock < $request->product_count) {
            notify()->error("Max count of current product is {$product->in_stock}", position: "topRight");
            return redirect()->back();
        }

        Cart::instance('cart')->update($request->rowId, $request->product_count);

        notify()->success("Product count was updated", position: "topRight");

        return redirect()->back();
    }
}
