<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','min:3','max:255','regex:/^[A-Za-z0-9\s\-]+$/',],

            'description' => ['nullable', 'string', 'max:2000', ],

            'type' => ['required','in:digital,course,laptop,event',],

            'price' => ['required','numeric','min:0','max:99999999.99',],

            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048',],

            'status' => ['required','boolean',],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }   

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required','string','min:3','max:255','regex:/^[A-Za-z0-9\s\-]+$/',],

            'description' => ['nullable', 'string', 'max:2000', ],

            'type' => ['required','in:digital,course,laptop,event',],

            'price' => ['required','numeric','min:0','max:99999999.99',],

            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048',],

            'status' => ['required','boolean',],
        ]);

        if ($request->hasFile('image')) {

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
