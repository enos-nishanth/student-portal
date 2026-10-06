<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 1)
            ->latest()
            ->get();

        return view('student.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        if (!$product->status) {
            abort(404);
        }

        return view('student.products.show', compact('product'));
    }
}