<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'deliveryAddress',
            'items.product',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => [
                'required',
                'in:pending,confirmed,processing,shipped,out_for_delivery,delivered,cancelled',
            ],
        ]);

        $order->update($validated);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }

    public function markAsPaid(Order $order)
    {
        if ($order->payment_method !== 'cod') {
            return back()->with(
                'error',
                'Only COD orders can be marked as paid manually.'
            );
        }

        if ($order->payment_status === 'paid') {
            return back()->with('error', 'This order is already paid.');
        }

        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with(
            'success',
            'COD payment recorded successfully.'
        );
    }
}
