@extends('layouts.student')

@section('title', 'Track Order')

@section('content')

@php
    $statuses = [
        'pending' => [
            'label' => 'Order Placed',
            'icon' => 'bi-receipt',
            'description' => 'Your order has been placed successfully.',
        ],
        'confirmed' => [
            'label' => 'Order Confirmed',
            'icon' => 'bi-check-circle',
            'description' => 'Your order has been confirmed.',
        ],
        'processing' => [
            'label' => 'Processing',
            'icon' => 'bi-gear',
            'description' => 'Your order is being prepared.',
        ],
        'shipped' => [
            'label' => 'Shipped',
            'icon' => 'bi-box-seam',
            'description' => 'Your order has been shipped.',
        ],
        'out_for_delivery' => [
            'label' => 'Out for Delivery',
            'icon' => 'bi-truck',
            'description' => 'Your order is on its way to you.',
        ],
        'delivered' => [
            'label' => 'Delivered',
            'icon' => 'bi-house-check',
            'description' => 'Your order has been delivered successfully.',
        ],
    ];

    $statusKeys = array_keys($statuses);

    $currentIndex = array_search($order->order_status, $statusKeys);

    $cancelled = $order->order_status === 'cancelled';

    $currentLabel = $statuses[$order->order_status]['label']
        ?? ucfirst(str_replace('_', ' ', $order->order_status));
@endphp

<style>
    .tracking-card {
        border: 1px solid #e9ecef;
        border-radius: 14px;
        background: #fff;
    }

    .tracking-timeline {
        position: relative;
        padding: 10px 0 10px 8px;
    }

    .tracking-step {
        position: relative;
        display: flex;
        gap: 18px;
        min-height: 105px;
    }

    .tracking-step:last-child {
        min-height: auto;
    }

    .tracking-marker {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 50%;
        background: #f8f9fa;
        color: #adb5bd;
        border: 2px solid #dee2e6;
    }

    .tracking-step:not(:last-child)::after {
        content: "";
        position: absolute;
        left: 21px;
        top: 45px;
        height: calc(100% - 45px);
        border-left: 3px dashed #dee2e6;
    }

    .tracking-step.completed:not(:last-child)::after {
        border-left-color: #198754;
    }

    .tracking-step.completed .tracking-marker {
        background: #198754;
        border-color: #198754;
        color: #fff;
    }

    .tracking-step.current .tracking-marker {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
        box-shadow: 0 0 0 5px rgba(13, 110, 253, .12);
    }

    .tracking-step.current .tracking-title {
        color: #0d6efd;
    }

    .tracking-content {
        padding: 2px 0 25px;
    }

    .tracking-title {
        font-weight: 600;
        margin-bottom: 5px;
        color: #212529;
    }

    .tracking-description {
        color: #6c757d;
        font-size: .925rem;
        margin-bottom: 0;
    }

    .tracking-step.pending .tracking-title {
        color: #6c757d;
    }

    .tracking-current-badge {
        font-size: .75rem;
        vertical-align: middle;
    }

    .order-product-item + .order-product-item {
        border-top: 1px solid #eee;
        padding-top: 16px;
        margin-top: 16px;
    }

    @media (max-width: 576px) {
        .tracking-step {
            gap: 13px;
            min-height: 115px;
        }

        .tracking-marker {
            width: 40px;
            height: 40px;
            min-width: 40px;
        }

        .tracking-step:not(:last-child)::after {
            left: 19px;
            top: 41px;
        }

        .tracking-card .card-body {
            padding: 1rem !important;
        }
    }
</style>

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="mb-1">
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

    {{-- Cancelled Order --}}
    @if ($cancelled)

        <div class="alert alert-danger">
            <i class="bi bi-x-circle me-2"></i>
            This order has been cancelled.
        </div>

    @else

        {{-- Vertical Order Tracking --}}
        <div class="card tracking-card shadow-sm mb-4">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                    <div>
                        <h5 class="mb-1">Delivery Progress</h5>
                        <p class="text-muted mb-0">
                            Follow your order's journey from placement to delivery.
                        </p>
                    </div>

                    <span class="badge bg-primary fs-6">
                        {{ $currentLabel }}
                    </span>
                </div>

                <hr class="mb-4">

                <div class="tracking-timeline">

                    @foreach ($statuses as $key => $status)

                        @php
                            $stepIndex = array_search($key, $statusKeys);

                            $completed = $currentIndex !== false
                                && $currentIndex !== null
                                && $stepIndex < $currentIndex;

                            $current = $order->order_status === $key;

                            $stepClass = $completed
                                ? 'completed'
                                : ($current ? 'current' : 'pending');
                        @endphp

                        <div class="tracking-step {{ $stepClass }}">

                            {{-- Status Icon --}}
                            <div class="tracking-marker">
                                @if ($completed)
                                    <i class="bi bi-check-lg fs-5"></i>
                                @else
                                    <i class="bi {{ $status['icon'] }} fs-5"></i>
                                @endif
                            </div>

                            {{-- Status Information --}}
                            <div class="tracking-content flex-grow-1">

                                <div class="tracking-title">
                                    {{ $status['label'] }}

                                    @if ($current)
                                        <span class="badge bg-primary tracking-current-badge ms-2">
                                            Current
                                        </span>
                                    @elseif ($completed)
                                        <span class="badge bg-success tracking-current-badge ms-2">
                                            Completed
                                        </span>
                                    @endif
                                </div>

                                <p class="tracking-description">
                                    {{ $status['description'] }}
                                </p>

                                @if ($current)
                                    <small class="text-primary d-block mt-2">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Your order is currently at this stage.
                                    </small>
                                @elseif (!$completed)
                                    <small class="text-muted d-block mt-2">
                                        Awaiting this stage.
                                    </small>
                                @endif

                            </div>
                        </div>

                    @endforeach

                </div>
            </div>
        </div>

    @endif

    {{-- Order Details --}}
    <div class="row g-4">

        {{-- Order Summary --}}
        <div class="col-lg-6">
            <div class="card tracking-card shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-receipt me-2"></i>
                        Order Summary
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between gap-3 mb-3">
                        <span class="text-muted">Order Number</span>
                        <strong class="text-end">
                            {{ $order->order_number }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between gap-3 mb-3">
                        <span class="text-muted">Order Date</span>
                        <span class="text-end">
                            {{ $order->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between gap-3 mb-3">
                        <span class="text-muted">Payment Method</span>
                        <strong>
                            {{ strtoupper($order->payment_method) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                        <span class="text-muted">Payment Status</span>

                        <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Total Amount</strong>
                        <strong class="fs-5 text-primary">
                            ₹{{ number_format($order->total_amount, 2) }}
                        </strong>
                    </div>

                </div>
            </div>
        </div>

        {{-- Products in Order --}}
        <div class="col-lg-6">
            <div class="card tracking-card shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-box-seam me-2"></i>
                        Products in This Order
                    </h5>
                </div>

                <div class="card-body">

                    @forelse ($order->items as $item)

                        <div class="order-product-item">
                            <div class="d-flex justify-content-between align-items-start gap-3">

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
                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            No products found for this order.
                        </p>

                    @endforelse

                </div>
            </div>
        </div>

    </div>

</div>

@endsection
