<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\RazorpayRefundService;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items')
            ->latest()
            ->get();

        return view('student.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        // Make sure the order belongs to the logged-in student
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load([
            'items',
            'deliveryAddress',
        ]);

        return view('student.orders.show', compact('order'));
    }

    
    public function track(\App\Models\Order $order)
    {
    
        abort_unless($order->user_id === auth()->id(), 404);

        // Match the orders visible in My Purchases.
        if (
            $order->payment_method !== 'cod' &&
            $order->payment_status !== 'paid'
        ) {
            abort(404);
        }

        return view('student.orders.track', compact('order'));
    }

    public function cancel(Order $order, RazorpayRefundService $refundService) 
    {
        //  Ensure the order belongs to the logged-in student.
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        //  Prevent cancelling an already-cancelled order.
        if ($order->order_status === 'cancelled') {
            return back()->with(
                'error',
                'This order is already cancelled.'
            );
        }

        //  Prevent cancelling shipped or completed orders.
        if (in_array($order->order_status, [
            'shipped',
            'out_for_delivery',
            'delivered',
        ], true)) {
            return back()->with(
                'error',
                'This order can no longer be cancelled.'
            );
        }

        //  Allow cancellation only within 24 hours.
        if (!$order->created_at->copy()->addHours(24)->isFuture()) {
            return back()->with(
                'error',
                'Orders can only be cancelled within 24 hours of placement.'
            );
        }

        //  Cancel the order.
        $order->update([
            'order_status' => 'cancelled',
        ]);

        //  Request a refund for successfully paid online orders.
        if (
            in_array($order->payment_method, ['upi', 'card'], true)
            && $order->payment_status === 'paid'
        ) {
            try {
                $refund = $refundService->refund($order);

                if ($refund['status'] === 'processed') {
                    return redirect()
                        ->route('student.orders.show', $order)
                        ->with(
                            'success',
                            'Order cancelled and refund processed successfully.'
                        );
                }

                return redirect()
                    ->route('student.orders.show', $order)
                    ->with(
                        'success',
                        'Order cancelled. Your refund request was submitted and is awaiting processing.'
                    );

            } catch (Throwable $e) {
                Log::error('Order refund request failed.', [
                    'order_id' => $order->id,
                    'message' => $e->getMessage(),
                ]);

                return redirect()
                    ->route('student.orders.show', $order)
                    ->with(
                        'error',
                        'Your order was cancelled, but the refund request could not be confirmed. Please contact support before retrying.'
                    );
            }
        }

        // COD and unpaid online orders don't need an immediate refund.
        return redirect()
            ->route('student.orders.show', $order)
            ->with(
                'success',
                'Your order has been cancelled successfully.'
            );
    }

    public function requestRefund(Order $order, RazorpayRefundService $refundService) 
    {
        // 1. Verify order ownership.
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        // 2. Only cancelled orders can request a refund.
        if ($order->order_status !== 'cancelled') {
            return back()->with(
                'error',
                'Please cancel your order before requesting a refund.'
            );
        }

        // 3. Only successfully paid online orders are eligible.
        if (
            !in_array($order->payment_method, ['upi', 'card'], true) ||
            $order->payment_status !== 'paid'
        ) {
            return back()->with(
                'error',
                'This order is not eligible for a Razorpay refund.'
            );
        }

        // 4. Prevent duplicate refund requests.
        if (in_array($order->refund_status, ['pending', 'processed'], true)) {
            return back()->with(
                'error',
                'A refund has already been requested or processed.'
            );
        }

        try {
            $refund = $refundService->refund($order);

            if ($refund['status'] === 'processed') {
                return back()->with(
                    'success',
                    'Your refund has been processed successfully.'
                );
            }

            return back()->with(
                'success',
                'Your refund request has been submitted and is awaiting processing.'
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error(
                'Refund request failed.',
                [
                    'order_id' => $order->id,
                    'message' => $e->getMessage(),
                ]
            );

            return back()->with(
                'error',
                'The refund request could not be confirmed. Check the refund status before retrying.'
            );
        }
    }
}