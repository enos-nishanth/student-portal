<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\RazorpayRefundService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderController extends Controller
{
    
    public function index(): JsonResponse
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items', 'deliveryAddress'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    
    public function show(Order $order): JsonResponse
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        $order->load(['items', 'deliveryAddress']);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    
    public function track(Order $order): JsonResponse
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        // COD orders can be tracked without online payment.
        // Online orders must be paid.
        if (
            $order->payment_method !== 'cod' &&
            $order->payment_status !== 'paid'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Tracking is not available for this order.',
            ], 404);
        }

        $order->load(['items', 'deliveryAddress']);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    
    public function cancel(
        Order $order,
        RazorpayRefundService $refundService
    ): JsonResponse {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if ($order->order_status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'This order is already cancelled.',
            ], 422);
        }

        if (in_array($order->order_status, [
            'shipped',
            'out_for_delivery',
            'delivered',
        ], true)) {
            return response()->json([
                'success' => false,
                'message' => 'This order can no longer be cancelled.',
            ], 422);
        }

        if (!$order->created_at->copy()->addHours(24)->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'Orders can only be cancelled within 24 hours of placement.',
            ], 422);
        }

        $order->update([
            'order_status' => 'cancelled',
        ]);

        // Request a refund for paid online orders.
        if (
            in_array($order->payment_method, ['upi', 'card'], true) &&
            $order->payment_status === 'paid'
        ) {
            try {
                $refund = $refundService->refund($order);

                return response()->json([
                    'success' => true,
                    'message' => $refund['status'] === 'processed'
                        ? 'Order cancelled and refund processed successfully.'
                        : 'Order cancelled. Refund request submitted and awaiting processing.',
                    'data' => [
                        'order_id' => $order->id,
                        'order_status' => $order->order_status,
                        'refund_status' => $refund['status'],
                    ],
                ]);
            } catch (Throwable $e) {
                Log::error('Order refund request failed.', [
                    'order_id' => $order->id,
                    'message' => $e->getMessage(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Order cancelled, but the refund request could not be confirmed. Please contact support before retrying.',
                    'data' => [
                        'order_id' => $order->id,
                        'order_status' => $order->order_status,
                    ],
                ], 502);
            }
        }

        // COD and unpaid online orders do not need an immediate refund.
        return response()->json([
            'success' => true,
            'message' => 'Your order has been cancelled successfully.',
            'data' => [
                'order_id' => $order->id,
                'order_status' => $order->order_status,
                'payment_status' => $order->payment_status,
            ],
        ]);
    }

    
    public function requestRefund(
        Order $order,
        RazorpayRefundService $refundService
    ): JsonResponse {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if ($order->order_status !== 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Please cancel your order before requesting a refund.',
            ], 422);
        }

        if (
            !in_array($order->payment_method, ['upi', 'card'], true) ||
            $order->payment_status !== 'paid'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'This order is not eligible for a Razorpay refund.',
            ], 422);
        }

        if (in_array($order->refund_status, ['pending', 'processed'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'A refund has already been requested or processed.',
            ], 422);
        }

        try {
            $refund = $refundService->refund($order);

            return response()->json([
                'success' => true,
                'message' => $refund['status'] === 'processed'
                    ? 'Your refund has been processed successfully.'
                    : 'Your refund request has been submitted and is awaiting processing.',
                'data' => [
                    'order_id' => $order->id,
                    'refund_status' => $refund['status'],
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Refund request failed.', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'The refund request could not be confirmed. Check the refund status before retrying.',
            ], 502);
        }
    }
}
