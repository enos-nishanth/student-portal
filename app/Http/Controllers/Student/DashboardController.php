<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 1)
            ->latest()
            ->take(5)
            ->get();

        $cart = auth()->user()->cart;

        $cartItemCount = $cart ? $cart->items()->count() : 0;

        return view('dashboard', compact(
            'products',
            'cartItemCount'
        ));
    }
}