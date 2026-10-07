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

        $addresses = auth()->user()
            ->deliveryAddresses()
            ->latest()
            ->get();

        return view('student.cart.index', compact('cart', 'addresses'));
    }

    public function checkout()
    {
        $addresses = auth()->user()
            ->deliveryAddresses()
            ->latest()
            ->get();

        // Check if this is a Buy Now checkout
        if (session()->has('buy_now')) {

            $buyNow = session('buy_now');

            $product = Product::find($buyNow['product_id']);

            if (!$product || !$product->status) {

                session()->forget('buy_now');

                return redirect()
                    ->route('student.products.index')
                    ->with('error', 'Product is no longer available.');
            }   

            $items = collect([
                (object) [
                    'product' => $product,
                    'quantity' => $buyNow['quantity'],
                ]
            ]);

        } else {

            // Normal cart checkout
            $cart = Cart::where('user_id', auth()->id())
                ->with('items.product')
                ->first();

            if (!$cart || $cart->items->count() === 0) {

                return redirect()
                    ->route('student.cart.index')
                    ->with('error', 'Your cart is empty.');
            }

            $items = $cart->items;
        }   

        return view('student.checkout', compact(
            'items',
            'addresses'
        ));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'delivery_address_id' => 'required|exists:delivery_addresses,id',
            'payment_method' => 'required|in:upi,card,cod',
        ]);

        // Make sure the address belongs to the logged-in user
        $address = auth()->user()
            ->deliveryAddresses()
            ->where('id', $request->delivery_address_id)
            ->first();

        if (!$address) {
            abort(403);
        }

        // Determine Checkout Source


        if (session()->has('buy_now')) {

            // Buy Now checkout
            $buyNow = session('buy_now');

            $product = Product::find($buyNow['product_id']);

            if (!$product || !$product->status) {

                session()->forget('buy_now');

                return redirect()
                    ->route('student.products.index')
                    ->with('error', 'Product is no longer available.');
            }

            $items = collect([
                (object) [
                    'product' => $product,
                    'quantity' => $buyNow['quantity'],
                ]
            ]);

            $checkoutSource = 'buy_now';

        } else {

            // Cart checkout
            $cart = Cart::where('user_id', auth()->id())
                ->with('items.product')
                ->first();

            if (!$cart || $cart->items->count() === 0) {

                return redirect()
                    ->route('student.cart.index')
                    ->with('error', 'Your cart is empty.');
            }

            $items = $cart->items;

            $checkoutSource = 'cart';
        }
        
        //Calculate Total
    

        $cartTotal = 0;

        foreach ($items as $item) {

            if (!$item->product->status) {

                return redirect()
                    ->route('student.cart.index')
                    ->with(
                        'error',
                        "{$item->product->name} is no longer available."
                    );
            }

            $cartTotal +=
                $item->product->price * $item->quantity;
        }

        //Create Order
    
        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),

            'delivery_address_id' => $address->id,

            'order_number' =>
                'ORD-' .
                now()->format('YmdHis') .
                '-' .
                auth()->id(),
            
            'payment_method' => $request->payment_method,

            'total_amount' => $cartTotal,

            'order_status' => 'pending',

            'payment_status' => 'pending',
        ]);

        //Create Order Items
        foreach ($items as $item) {

            $price = $item->product->price;

            $order->items()->create([
                'product_id' => $item->product->id,

                'product_name' => $item->product->name,

                'price' => $price,

                'quantity' => $item->quantity,

                'total' => $price * $item->quantity,
            ]);
        }


        // Temporary Response
        return redirect()
            ->route('student.checkout')
            ->with(
                'success',
                "Order {$order->order_number} created successfully."
            );
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

        // Store Buy Now information in session
        session([
            'buy_now' => [
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ],
        ]);

        return redirect()
            ->route('student.checkout');
    }
}