<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;

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

    public function cancel(Order $order)
    {
        // Ensure the order belongs to the logged-in student.
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        // Prevent cancelling an already-cancelled order.
        if ($order->order_status === 'cancelled') {
            return back()->with('error', 'This order is already cancelled.');
        }

        // Prevent cancelling shipped or completed orders.
        if (in_array($order->order_status, [
            'shipped',
            'out_for_delivery',
            'delivered',
        ])) {
            return back()->with(
                'error',
                'This order can no longer be cancelled.'
            );
        }

        // Allow cancellation only within 24 hours.
        if (!$order->created_at->addHours(24)->isFuture()) {
            return back()->with(
                'error',
                'Orders can only be cancelled within 24 hours of placement.'
            );
        }

        // Cancel the order.
        $order->update([
            'order_status' => 'cancelled',
        ]);

        return redirect()
            ->route('student.orders.show', $order)
            ->with('success', 'Your order has been cancelled successfully.');
    }

}