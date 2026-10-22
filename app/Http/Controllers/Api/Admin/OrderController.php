<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::with([
                'user:id,name,email',
            ])
                ->latest()
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Orders retrieved successfully.',
                'data' => $orders,
            ], 200);
        } catch (Throwable $exception) {
            Log::error('Failed to retrieve orders.', [
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve orders at this time.',
            ], 500);
        }
    }

    public function show(Order $order)
    {
        try {
            $order->load([
                'user:id,name,email',
                'deliveryAddress',
                'items.product',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order retrieved successfully.',
                'data' => $order,
            ], 200);
        } catch (Throwable $exception) {
            Log::error('Failed to retrieve order.', [
                'order_id' => $order->id,
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve this order at this time.',
            ], 500);
        }
    }

    
    public function updateStatus(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'order_status' => [
                'required',
                'in:pending,confirmed,processing,shipped,out_for_delivery,delivered,cancelled',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $order->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.',
                'data' => $order->fresh(),
            ], 200);
        } catch (Throwable $exception) {
            Log::error('Failed to update order status.', [
                'order_id' => $order->id,
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to update the order status at this time.',
            ], 500);
        }
    }

    
    public function markAsPaid(Order $order)
    {
        if ($order->payment_method !== 'cod') {
            return response()->json([
                'success' => false,
                'message' => 'Only COD orders can be marked as paid manually.',
            ], 422);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This order is already paid.',
            ], 422);
        }

        try {
            DB::transaction(function () use ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'COD payment recorded successfully.',
                'data' => $order->fresh(),
            ], 200);
        } catch (Throwable $exception) {
            Log::error('Failed to mark COD order as paid.', [
                'order_id' => $order->id,
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to record the payment at this time.',
            ], 500);
        }
    }
}
