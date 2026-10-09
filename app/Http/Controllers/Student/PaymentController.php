<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = \App\Models\Order::where('id', $request->order_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$order) {
            abort(403);
        }

        // Only UPI and Card use Razorpay
        if (!in_array($order->payment_method, ['upi', 'card'])) {
            return response()->json([
                'message' => 'Razorpay is not required for this payment method.'
            ], 422);
        }

        // Create Razorpay API instance
        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        // Create Razorpay order
        $razorpayOrder = $api->order->create([
            'receipt' => $order->order_number,
            'amount' => (int) round($order->total_amount * 100),
            'currency' => 'INR',
        ]);

        // Save Razorpay order ID
        $order->update([
            'razorpay_order_id' => $razorpayOrder['id'],
        ]);

        return response()->json([
            'order_id' => $order->id,
            'razorpay_order_id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency'],
            'key' => config('services.razorpay.key'),
        ]);
    }

    public function show(\App\Models\Order $order)
    {
        // Make sure the order belongs to the logged-in student
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Only pending online payments can come here
        if (
            !in_array($order->payment_method, ['upi', 'card']) ||
            $order->payment_status !== 'pending'
        ) {
            return redirect()
                ->route('student.products.index')
                ->with('error', 'This order cannot be paid online.');
        }

        $order->load('items');

        return view('student.payment', compact('order'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $checkoutSource = session('checkout_source', 'cart');

        $order = \App\Models\Order::where('id', $request->order_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$order) {
        abort(403);
        }

        // Make sure this Razorpay order belongs to our local order

        if ($order->razorpay_order_id !== $request->razorpay_order_id) {

            return response()->json([
                'message' => 'Invalid Razorpay order.'
            ], 422);
        }

        try {

            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);

            $order->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'payment_status' => 'paid',
                'order_status' => 'confirmed',
                'paid_at' => now(),
            ]);

            // Clear cart only after successful payment verification

            if ($checkoutSource === 'cart') {

                $cart = \App\Models\Cart::where('user_id', auth()->id())
                    ->first();

                if ($cart) {
                    $cart->items()->delete();
                }
            }

            session()->forget([
                'buy_now',
                'checkout_source',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully.',
                'redirect' => route('student.orders.show', $order),
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed.'
            ], 422);
        }
    }
}