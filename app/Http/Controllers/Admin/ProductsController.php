<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductsController extends Controller
{
    public function __construct(protected ProductRepository $repository)
    {
    }

    public function index()
    {
        $products = Product::with('category')->paginate(5);

        return view('admin/products/index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin/products/create', compact('categories'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin/products/edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        if ($this->repository->update($product, $request)) {
            notify()->success('Product successfully updated', position: 'topRight');

            return redirect()->route('admin.products.index');
        } else {
            notify()->error('Oops smth went wrong', position: 'topRight');

            return redirect()->back()->withInput();
        }
    }

    public function store(CreateProductRequest $request)
    {
        if ($this->repository->create($request)) {
            return redirect()->route('admin.products.index');
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index');
    }
}
