<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;

class CartController extends Controller
{
    // Add product to cart
    public function add(Request $request, Product $product)
    {
        // Validate quantity
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check product status
        if (!$product->status) {
            abort(404);
        }

        // Get or create user's cart
        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        // Check whether product already exists in cart
        $cartItem = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {

            // Add selected quantity to existing quantity
            $cartItem->increment(
                'quantity',
                $request->quantity
            );

        } else {

            // Create new cart item
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()
            ->route('student.cart.index')
            ->with('success', 'Product added to cart.');
    }


    // List products added to cart
    public function index()
    {
        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $cart->load('items.product');

        return view('student.cart.index', compact('cart'));
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if cart item belongs to logged-in user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()
            ->route('student.cart.index')
            ->with('success', 'Cart updated successfully.');
    }


    public function remove(CartItem $cartItem)
    {
        // Check if cart item belongs to logged-in user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()
            ->route('student.cart.index')
            ->with('success', 'Product removed from cart.');
    }   

    public function buyNow(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check product status
        if (!$product->status) {
            abort(404);
        }

        return redirect()
            ->route('student.products.index')
            ->with(
                'success',
                "Dummy Buy Now: {$product->name} × {$request->quantity}"
            );
    }
}