<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use RuntimeException;

class RazorpayRefundService
{
    public function refund(Order $order): array
    {
        // 1. Only successfully paid online orders can be refunded.
        if (!in_array($order->payment_method, ['upi', 'card'], true)) {
            throw new RuntimeException(
                'Only online payments can be refunded through Razorpay.'
            );
        }

        if ($order->payment_status !== 'paid') {
            throw new RuntimeException(
                'Only paid orders can be refunded.'
            );
        }

        if (empty($order->razorpay_payment_id)) {
            throw new RuntimeException(
                'The Razorpay payment ID is missing.'
            );
        }

        if (in_array($order->refund_status, ['pending', 'processed'], true)) {
            throw new RuntimeException(
                'A refund has already been requested for this order.'
            );
        }

        //api config
        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        // 3. Request a full refund.
        $refund = $api->payment
            ->fetch($order->razorpay_payment_id)
            ->refund([
                'amount' => (int) round((float) $order->total_amount * 100),
                'speed' => 'normal',
                'notes' => [
                    'order_number' => $order->order_number,
                    'reason' => $order->refund_reason ?? 'Order cancellation',
                ],
            ]);

        // 4. Save the refund reference and status.
        DB::transaction(function () use ($order, $refund) {
            $order->update([
                'refund_status' => $refund['status'] === 'processed'
                    ? 'processed'
                    : 'pending',

                'razorpay_refund_id' => $refund['id'],

                'refund_amount' => round(
                    (float) $order->total_amount,
                    2
                ),

                'refund_requested_at' => now(),

                'refunded_at' => $refund['status'] === 'processed'
                    ? now()
                    : null,

                'payment_status' => $refund['status'] === 'processed'
                    ? 'refunded'
                    : 'paid',
            ]);
        });

        return [
            'refund_id' => $refund['id'],
            'status' => $refund['status'],
            'amount' => $order->total_amount,
        ];
    }
}