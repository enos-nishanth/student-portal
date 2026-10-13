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

}