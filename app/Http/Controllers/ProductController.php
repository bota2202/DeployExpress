<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index', ['products' => Product::orderBy('name')->get()]);
    }

    public function create()
    {
        return view('products.form', ['product' => new Product]);
    }

    public function edit(Product $product)
    {
        return view('products.form', ['product' => $product]);
    }

    public function store(Request $request)
    {
        Product::create($this->validated($request));

        return redirect()->route('products.index')->with('ok', 'Produto cadastrado.');
    }

    public function update(Request $request, Product $product)
    {
        $product->update($this->validated($request, $product));

        return redirect()->route('products.index')->with('ok', 'Produto atualizado.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:30', Rule::unique('products')->ignore($product)],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight_kg' => 'nullable|numeric|min:0',
        ]);
    }
}
