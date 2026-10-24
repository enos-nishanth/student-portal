<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Razorpay\Api\Api;
use Throwable;

class PaymentController extends Controller
{
    /**
     * Create a Razorpay order for an existing local order.
     */
    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
        ]);

        $order = Order::where('id', $validated['order_id'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if (!in_array($order->payment_method, ['upi', 'card'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Razorpay is not required for this payment method.',
            ], 422);
        }

        if ($order->payment_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This order is not awaiting payment.',
            ], 422);
        }

        try {
            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            // Reuse the existing Razorpay order if one was already created.
            if (!$order->razorpay_order_id) {
                $razorpayOrder = $api->order->create([
                    'receipt' => $order->order_number,
                    'amount' => (int) round(
                        (float) $order->total_amount * 100
                    ),
                    'currency' => 'INR',
                ]);

                $order->update([
                    'razorpay_order_id' => $razorpayOrder['id'],
                ]);
            } else {
                $razorpayOrder = $api->order->fetch(
                    $order->razorpay_order_id
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Razorpay order is ready.',
                'data' => [
                    'order_id' => $order->id,
                    'razorpay_order_id' => $razorpayOrder['id'],
                    'amount' => $razorpayOrder['amount'],
                    'currency' => $razorpayOrder['currency'],
                    'key' => config('services.razorpay.key'),
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Razorpay order creation failed.', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to create the payment order. Please try again.',
            ], 502);
        }
    }

    /**
     * Return payment details as JSON.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if (
            !in_array($order->payment_method, ['upi', 'card'], true) ||
            $order->payment_status !== 'pending'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'This order cannot be paid online.',
            ], 422);
        }

        $order->load('items');

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $order,
                'razorpay_key' => config('services.razorpay.key'),
            ],
        ]);
    }

    /**
     * Verify Razorpay's signature and update the local order.
     */
    public function verify(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $order = Order::where('id', $validated['order_id'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This order has already been paid.',
            ], 409);
        }

        if (
            !in_array($order->payment_method, ['upi', 'card'], true) ||
            $order->payment_status !== 'pending'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'This order is not awaiting online payment.',
            ], 422);
        }

        if (
            !$order->razorpay_order_id ||
            $order->razorpay_order_id !== $validated['razorpay_order_id']
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Razorpay order.',
            ], 422);
        }

        try {
            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);

            $order->update([
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
                'payment_status' => 'paid',
                'order_status' => 'confirmed',
                'paid_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully.',
                'data' => [
                    'order_id' => $order->id,
                    'payment_status' => $order->payment_status,
                    'order_status' => $order->order_status,
                ],
            ]);
        } catch (Throwable $e) {
            Log::warning('Razorpay payment verification failed.', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed.',
            ], 422);
        }
    }
}
