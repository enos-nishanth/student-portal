
@extends('layouts.student')

@section('title', 'Track Order')

@section('content')

@php
    $statuses = [
        'pending' => 'Order Placed',
        'confirmed' => 'Order Confirmed',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'out_for_delivery' => 'Out for Delivery',
        'delivered' => 'Delivered',
    ];

    $statusKeys = array_keys($statuses);
    $currentIndex = array_search($order->order_status, $statusKeys);
    $cancelled = $order->order_status === 'cancelled';
@endphp

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>
                <i class="bi bi-truck me-2"></i>
                Track Your Order
            </h3>

            <p class="text-muted mb-0">
                Order #{{ $order->order_number }}
            </p>
        </div>

        <a href="{{ route('student.orders.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            My Purchases
        </a>
    </div>

    @if ($cancelled)
        <div class="alert alert-danger">
            <i class="bi bi-x-circle me-2"></i>
            This order has been cancelled.
        </div>
    @else
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1">Delivery Progress</h5>
                        <p class="text-muted mb-0">
                            Follow your order's current status below.
                        </p>
                    </div>

                    <span class="badge bg-primary align-self-start fs-6">
                        {{ $statuses[$order->order_status] ?? ucfirst($order->order_status) }}
                    </span>
                </div>

                <hr>

                <div class="list-group list-group-flush">
                    @foreach ($statuses as $key => $label)
                        @php
                            $stepIndex = array_search($key, $statusKeys);

                            $completed = $currentIndex !== false
                                && $currentIndex !== null
                                && $stepIndex <= $currentIndex;

                            $current = $order->order_status === $key;
                        @endphp

                        <div class="list-group-item px-0 py-3 border-0">
                            <div class="d-flex align-items-start gap-3">
                                <div
                                    class="rounded-circle d-flex align-items-center justify-content-center
                                        {{ $completed ? 'bg-success text-white' : 'bg-light text-secondary border' }}"
                                    style="width: 40px; height: 40px; min-width: 40px;"
                                >
                                    @if ($completed)
                                        <i class="bi bi-check-lg fs-5"></i>
                                    @else
                                        <i class="bi bi-circle"></i>
                                    @endif
                                </div>

                                <div class="flex-grow-1 pt-1">
                                    <div class="fw-semibold">
                                        {{ $label }}

                                        @if ($current)
                                            <span class="badge bg-primary ms-2">
                                                Current
                                            </span>
                                        @endif
                                    </div>

                                    @if ($current)
                                        <small class="text-muted">
                                            Your order is currently at this stage.
                                        </small>
                                    @elseif (!$completed)
                                        <small class="text-muted">
                                            Awaiting this stage.
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    @endif

    <div class="row g-4">

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Order Summary</h5>
                </div>

                <div class="card-body">
                    <p>
                        <strong>Order Number:</strong>
                        {{ $order->order_number }}
                    </p>

                    <p>
                        <strong>Order Date:</strong>
                        {{ $order->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                    </p>

                    <p>
                        <strong>Payment Method:</strong>
                        {{ strtoupper($order->payment_method) }}
                    </p>

                    <p>
                        <strong>Payment Status:</strong>
                        <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </p>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <strong>Total Amount</strong>
                        <strong>
                            ₹{{ number_format($order->total_amount, 2) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Products in This Order</h5>
                </div>

                <div class="card-body">
                    @foreach ($order->items as $item)
                        <div class="d-flex justify-content-between gap-3 mb-3">
                            <div>
                                <div class="fw-semibold">
                                    {{ $item->product_name }}
                                </div>

                                <small class="text-muted">
                                    Quantity: {{ $item->quantity }}
                                </small>
                            </div>

                            <div class="fw-semibold text-nowrap">
                                ₹{{ number_format($item->total, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
