<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __construct(protected ProductRepository $repository)
    {

    }

    public function index()
    {
        $products = Product::with('category')->paginate(5);
        return view('admin/products/index',compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin/products/create',compact('categories'));
    }

    public function edit(Product $product)
    {
        return view('admin/products/edit',compact('product'));
    }

    public function  store(CreateProductRequest $request)
    {
        dd($this->repository->create($request));
        return redirect()->route('admin.products.index');
    }

}
