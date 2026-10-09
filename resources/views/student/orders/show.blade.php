@extends('layouts.student')

@section('title', 'Order Details')

@section('content')

<div class="container-fluid py-4">

    {{-- Success Message --}}
    @if ($order->payment_status === 'paid')

        <div class="alert alert-success d-flex align-items-center mb-4">

            <i class="bi bi-check-circle-fill fs-4 me-3"></i>

            <div>
                <h5 class="mb-1">
                    Payment Successful!
                </h5>

                <div>
                    Your order has been confirmed successfully.
                </div>
            </div>

        </div>

    @endif


    {{-- Order Header --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-1">
                        Order Details
                    </h4>

                    <div class="text-muted">
                        Order #{{ $order->order_number }}
                    </div>

                </div>

                <div class="text-end">

                    <span class="badge
                        {{ $order->order_status === 'confirmed'
                            ? 'bg-success'
                            : 'bg-secondary' }}
                    ">
                        {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- Order Items --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Order Items
                    </h5>

                </div>

                <div class="card-body">

                    @foreach ($order->items as $item)

                        <div class="d-flex justify-content-between
                                    align-items-center
                                    border-bottom
                                    pb-3
                                    mb-3">

                            <div>

                                <h6 class="mb-1">
                                    {{ $item->product_name }}
                                </h6>

                                <small class="text-muted">
                                    ₹{{ number_format($item->price, 2) }}
                                    ×
                                    {{ $item->quantity }}
                                </small>

                            </div>

                            <div class="fw-semibold">

                                ₹{{ number_format($item->total, 2) }}

                            </div>

                        </div>

                    @endforeach


                    {{-- Total --}}

                    <div class="d-flex justify-content-between
                                fs-5
                                fw-bold">

                        <span>
                            Total
                        </span>

                        <span>
                            ₹{{ number_format($order->total_amount, 2) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- Order Information --}}
        <div class="col-lg-4">

            {{-- Payment --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Payment
                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted">
                            Payment Method
                        </small>

                        <div class="fw-semibold">
                            {{ strtoupper($order->payment_method) }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Payment Status
                        </small>

                        <div>

                            <span class="badge bg-success">
                                {{ ucfirst($order->payment_status) }}
                            </span>

                        </div>

                    </div>


                    @if ($order->paid_at)

                        <div>

                            <small class="text-muted">
                                Paid At
                            </small>

                            <div>
                                {{ $order->paid_at->format('d M Y, h:i A') }}
                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Delivery Address --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Delivery Address
                    </h5>

                </div>

                <div class="card-body">

                    <div class="fw-semibold mb-2">
                        {{ ucfirst($order->deliveryAddress->type) }}
                    </div>

                    <div>
                        {{ $order->deliveryAddress->address }}
                    </div>

                    @if ($order->deliveryAddress->town)

                        <div>
                            {{ $order->deliveryAddress->town }}
                        </div>

                    @endif

                    <div>
                        {{ $order->deliveryAddress->city }}
                    </div>

                    <div>
                        {{ $order->deliveryAddress->pincode }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Actions --}}
    <div class="mt-4">

        <a
            href="{{ route('student.products.index') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-shop me-1"></i>
            Continue Shopping
        </a>

        <a
            href="{{ route('dashboard') }}"
            class="btn btn-outline-secondary ms-2"
        >
            <i class="bi bi-house me-1"></i>
            Dashboard
        </a>

    </div>

</div>

@endsection