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
}