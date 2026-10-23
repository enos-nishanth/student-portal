<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

class CartController extends Controller
{
    /**
     * Get the authenticated student's cart.
     */
    public function index()
    {
        try {
            $cart = Cart::firstOrCreate([
                'user_id' => auth()->id(),
            ]);

            $cart->load('items.product');

            return response()->json([
                'success' => true,
                'message' => 'Cart retrieved successfully.',
                'data' => $cart,
            ], 200);

        } catch (Throwable $exception) {
            Log::error('Failed to retrieve cart.', [
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve the cart at this time.',
            ], 500);
        }
    }

    
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $validated = $validator->validated();

            $result = DB::transaction(function () use ($validated) {
                $product = Product::whereKey($validated['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$product || !$product->status) {
                    throw ValidationException::withMessages([
                        'product_id' => ['This product is unavailable.'],
                    ]);
                }

                $cart = Cart::firstOrCreate([
                    'user_id' => auth()->id(),
                ]);

                $cartItem = $cart->items()
                    ->where('product_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                if ($cartItem) {
                    $cartItem->increment(
                        'quantity',
                        $validated['quantity']
                    );
                    $cartItem->refresh();
                } else {
                    $cartItem = $cart->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $validated['quantity'],
                    ]);
                }

                return $cartItem->load('product');
            });

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully.',
                'data' => $result,
            ], 201);

        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'The selected product is unavailable.',
                'errors' => $exception->errors(),
            ], 422);

        } catch (Throwable $exception) {
            Log::error('Failed to add product to cart.', [
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to add the product to the cart.',
            ], 500);
        }
    }

    
    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this cart item.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            if (!$cartItem->product || !$cartItem->product->status) {
                return response()->json([
                    'success' => false,
                    'message' => 'This product is unavailable.',
                ], 422);
            }

            $cartItem->update([
                'quantity' => $validator->validated()['quantity'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cart quantity updated successfully.',
                'data' => $cartItem->fresh()->load('product'),
            ], 200);

        } catch (Throwable $exception) {
            Log::error('Failed to update cart item.', [
                'cart_item_id' => $cartItem->id,
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to update the cart item.',
            ], 500);
        }
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to remove this cart item.',
            ], 403);
        }

        try {
            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart successfully.',
            ], 200);

        } catch (Throwable $exception) {
            Log::error('Failed to remove cart item.', [
                'cart_item_id' => $cartItem->id,
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to remove the product from the cart.',
            ], 500);
        }
    }

    
    public function buyNow(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!$product->status) {
            return response()->json([
                'success' => false,
                'message' => 'This product is unavailable.',
            ], 404);
        }

        $quantity = (int) $validator->validated()['quantity'];

        return response()->json([
            'success' => true,
            'message' => 'Buy Now checkout preview retrieved successfully.',
            'data' => [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'total' => round((float) $product->price * $quantity, 2),
            ],
        ], 200);
    }

    
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'checkout_type' => ['sometimes', 'in:cart,buy_now'],
            'product_id' => ['required_if:checkout_type,buy_now', 'integer', 'exists:products,id'],
            'quantity' => ['required_if:checkout_type,buy_now', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $validated = $validator->validated();
            $checkoutType = $validated['checkout_type'] ?? 'cart';

            if ($checkoutType === 'buy_now') {
                $product = Product::find($validated['product_id']);

                if (!$product || !$product->status) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This product is unavailable.',
                    ], 404);
                }

                $quantity = (int) $validated['quantity'];

                $items = [[
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => (float) $product->price,
                    'quantity' => $quantity,
                    'total' => round((float) $product->price * $quantity, 2),
                ]];
            } else {
                $cart = Cart::where('user_id', auth()->id())
                    ->with('items.product')
                    ->first();

                if (!$cart || $cart->items->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your cart is empty.',
                    ], 422);
                }

                $items = [];

                foreach ($cart->items as $cartItem) {
                    if (!$cartItem->product || !$cartItem->product->status) {
                        return response()->json([
                            'success' => false,
                            'message' => 'A product in your cart is unavailable.',
                        ], 422);
                    }

                    $price = (float) $cartItem->product->price;

                    $items[] = [
                        'product_id' => $cartItem->product->id,
                        'name' => $cartItem->product->name,
                        'price' => $price,
                        'quantity' => (int) $cartItem->quantity,
                        'total' => round($price * $cartItem->quantity, 2),
                    ];
                }
            }

            $total = round(array_sum(array_column($items, 'total')), 2);

            $addresses = auth()->user()
                ->deliveryAddresses()
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Checkout information retrieved successfully.',
                'data' => [
                    'checkout_type' => $checkoutType,
                    'items' => $items,
                    'total' => $total,
                    'delivery_addresses' => $addresses,
                    'payment_methods' => ['upi', 'card', 'cod'],
                ],
            ], 200);

        } catch (Throwable $exception) {
            Log::error('Failed to retrieve checkout information.', [
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve checkout information.',
            ], 500);
        }
    }

    
    public function placeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_address_id' => ['required', 'integer', 'exists:delivery_addresses,id'],
            'payment_method' => ['required', 'in:upi,card,cod'],
            'checkout_type' => ['sometimes', 'in:cart,buy_now'],
            'product_id' => ['required_if:checkout_type,buy_now', 'integer', 'exists:products,id'],
            'quantity' => ['required_if:checkout_type,buy_now', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $validated = $validator->validated();
            $user = auth()->user();

            $address = $user->deliveryAddresses()
                ->whereKey($validated['delivery_address_id'])
                ->first();

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected delivery address does not belong to you.',
                ], 403);
            }

            $checkoutType = $validated['checkout_type'] ?? 'cart';

            $order = DB::transaction(function () use (
                $validated,
                $user,
                $checkoutType
            ) {
                if ($checkoutType === 'buy_now') {
                    $product = Product::whereKey($validated['product_id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$product || !$product->status) {
                        throw ValidationException::withMessages([
                            'product_id' => ['This product is unavailable.'],
                        ]);
                    }

                    $items = [[
                        'product' => $product,
                        'quantity' => (int) $validated['quantity'],
                    ]];

                    $cart = null;
                } else {
                    $cart = Cart::where('user_id', $user->id)
                        ->with('items.product')
                        ->lockForUpdate()
                        ->first();

                    if (!$cart || $cart->items->isEmpty()) {
                        throw ValidationException::withMessages([
                            'cart' => ['Your cart is empty.'],
                        ]);
                    }

                    $items = [];

                    foreach ($cart->items as $cartItem) {
                        $product = Product::whereKey($cartItem->product_id)
                            ->lockForUpdate()
                            ->first();

                        if (!$product || !$product->status) {
                            throw ValidationException::withMessages([
                                'cart' => ['A product in your cart is unavailable.'],
                            ]);
                        }

                        $items[] = [
                            'product' => $product,
                            'quantity' => (int) $cartItem->quantity,
                        ];
                    }
                }

                $total = 0;

                foreach ($items as $item) {
                    $total += (float) $item['product']->price * $item['quantity'];
                }

                $total = round($total, 2);

                $order = Order::create([
                    'user_id' => $user->id,
                    'delivery_address_id' => $validated['delivery_address_id'],
                    'order_number' => 'ORD-' . now()->format('YmdHis')
                        . '-' . $user->id . '-' . bin2hex(random_bytes(3)),
                    'payment_method' => $validated['payment_method'],
                    'total_amount' => $total,
                    'order_status' => $validated['payment_method'] === 'cod'
                        ? 'confirmed'
                        : 'pending',
                    'payment_status' => 'pending',
                ]);

                foreach ($items as $item) {
                    $product = $item['product'];
                    $quantity = $item['quantity'];
                    $price = (float) $product->price;

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $price,
                        'quantity' => $quantity,
                        'total' => round($price * $quantity, 2),
                    ]);
                }

                // Do not clear the cart for online payments.
                // Payment must be verified before clearing it.
                if (
                    $validated['payment_method'] === 'cod'
                    && $checkoutType === 'cart'
                ) {
                    $cart->items()->delete();
                }

                return $order->load('items');
            });

            return response()->json([
                'success' => true,
                'message' => $order->payment_method === 'cod'
                    ? 'COD order placed successfully.'
                    : 'Order created. Complete online payment to confirm payment.',
                'data' => $order,
            ], 201);

        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to place the order.',
                'errors' => $exception->errors(),
            ], 422);

        } catch (Throwable $exception) {
            Log::error('Failed to place order.', [
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to place the order at this time.',
            ], 500);
        }
    }
}
