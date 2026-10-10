@extends('layouts.admin')

@section('title', 'Orders')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h3>
            <i class="bi bi-box-seam me-2"></i>
            Orders
        </h3>

        <p class="text-muted">
            Manage student orders and payments.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="px-4">Order Number</th>
                            <th>Student</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-4">
                                    <strong>{{ $order->order_number }}</strong>
                                </td>

                                <td>
                                    {{ $order->user->name ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $order->created_at
                                        ->timezone('Asia/Kolkata')
                                        ->format('d M Y, h:i A') }}
                                </td>

                                <td>
                                    <strong>
                                        ₹{{ number_format($order->total_amount, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    {{ strtoupper($order->payment_method) }}
                                </td>

                                <td>
                                    @if($order->payment_status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($order->payment_status === 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @elseif($order->payment_status === 'refunded')
                                        <span class="badge bg-info">Refunded</span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($order->order_status === 'confirmed')
                                        <span class="badge bg-success">Confirmed</span>
                                    @elseif($order->order_status === 'processing')
                                        <span class="badge bg-info">Processing</span>
                                    @elseif($order->order_status === 'shipped')
                                        <span class="badge bg-primary">Shipped</span>
                                    @elseif($order->order_status === 'out_for_delivery')
                                        <span class="badge bg-warning text-dark">
                                            Out for Delivery
                                        </span>
                                    @elseif($order->order_status === 'delivered')
                                        <span class="badge bg-success">Delivered</span>
                                    @elseif($order->order_status === 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    @endif
                                </td>
                                
                                <td>
                                    <a
                                          href="{{ route('admin.orders.show', $order) }}"
                                          class="btn btn-sm btn-primary mb-1"
                                    >
                                          <i class="bi bi-eye me-1"></i>
                                          View Order
                                    </a>

                                    @if (
                                          in_array($order->payment_method, ['upi', 'card']) &&
                                          in_array($order->payment_status, ['pending', 'failed']) &&
                                          $order->order_status === 'pending'
                                    )
                                          <span class="badge bg-warning text-dark d-block mt-1">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                Review Payment
                                          </span>
                                    @endif
                               </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2 mb-0">
                                        No orders found.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@endsection
