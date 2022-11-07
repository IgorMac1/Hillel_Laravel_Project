<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function __invoke(Request $request)
    {
        dd($request);

        /*
         * 1 - validate all fields
         * 2 - create order (set order status id) (set user id)
         * 3 - get all product from Cart::instance('cart')->content()
         * 4 - create order products records in db
         */
    }
}
