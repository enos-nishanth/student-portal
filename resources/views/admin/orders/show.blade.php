
@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
@php
    $address = $order->deliveryAddress;
@endphp

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Order Details</h3>
            <p class="text-muted mb-0">
                Order #{{ $order->order_number }}
            </p>
        </div>

        <a href="{{ route('admin.orders.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Orders
        </a>
    </div>

    {{-- Messages --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="row g-4">

        {{-- Customer Details --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person me-2"></i>
                        Customer Details
                    </h5>
                </div>

                <div class="card-body">
                    <p>
                        <strong>Name:</strong>
                        {{ $order->user->name ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Email:</strong>
                        {{ $order->user->email ?? 'N/A' }}
                    </p>

                    <p class="mb-0">
                        <strong>Phone:</strong>
                        {{ $order->user->phone ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Delivery Address --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-geo-alt me-2"></i>
                        Delivery Address
                    </h5>
                </div>

                <div class="card-body">
                    @if ($address)
                        <p>
                            <strong>Address:</strong>
                            {{ $address->address ?? 'N/A' }}
                        </p>

                        <p>
                            <strong>Town:</strong>
                            {{ $address->town ?? 'N/A' }}
                        </p>

                        <p>
                            <strong>City:</strong>
                            {{ $address->city ?? 'N/A' }}
                        </p>

                        <p>
                            <strong>Pincode:</strong>
                            {{ $address->pincode ?? 'N/A' }}
                        </p>

                        @if ($address && $address->latitude && $address->longitude)
                        <a
                              href="https://www.google.com/maps?q={{ $address->latitude }},{{ $address->longitude }}"
                              target="_blank"
                              rel="noopener noreferrer"
                              class="btn btn-outline-primary mt-3"
                        >
                              <i class="bi bi-geo-alt-fill me-1"></i>
                              Open in Google Maps
                        </a>
                        
                        @endif
                    @else
                        <p class="text-muted mb-0">
                            No delivery address available.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Ordered Products --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-box-seam me-2"></i>
                        Ordered Products
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                @if ($item->product?->image)
                                                    <img
                                                        src="{{ asset('storage/' . $item->product->image) }}"
                                                        alt="{{ $item->product_name }}"
                                                        width="60"
                                                        height="60"
                                                        class="rounded border"
                                                        style="object-fit: cover;"
                                                    >
                                                @endif

                                                <span class="fw-semibold">
                                                    {{ $item->product_name }}
                                                </span>
                                            </div>
                                        </td>

                                        <td>
                                            ₹{{ number_format($item->price, 2) }}
                                        </td>

                                        <td>{{ $item->quantity }}</td>

                                        <td class="fw-semibold">
                                            ₹{{ number_format($item->total, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Summary and Payment --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-receipt me-2"></i>
                        Order Summary
                    </h5>
                </div>

                <div class="card-body">
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
                        <h5>Total Amount</h5>
                        <h5>
                            ₹{{ number_format($order->total_amount, 2) }}
                        </h5>
                    </div>

                    @if (
                        $order->payment_method === 'cod' &&
                        $order->payment_status !== 'paid'
                    )
                        <form
                            action="{{ route('admin.orders.markAsPaid', $order) }}"
                            method="POST"
                            class="mt-3"
                            onsubmit="return confirm('Confirm that you have collected the COD payment?')"
                        >
                            @csrf
                            @method('PATCH')

                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-cash-coin me-1"></i>
                                Mark COD as Paid
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Delivery Status --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-truck me-2"></i>
                        Delivery Management
                    </h5>
                </div>

                <div class="card-body">
                    <p>
                        <strong>Current Status:</strong>
                        <span class="badge bg-primary">
                            {{ ucwords(str_replace('_', ' ', $order->order_status)) }}
                        </span>
                    </p>

                    <form
                        action="{{ route('admin.orders.updateStatus', $order) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PATCH')

                        <label for="order_status" class="form-label">
                            Update Delivery Status
                        </label>

                        <select
                            name="order_status"
                            id="order_status"
                            class="form-select mb-3"
                            required
                        >
                            @foreach ([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'out_for_delivery' => 'Out for Delivery',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ] as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @selected($order->order_status === $value)
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle me-1"></i>
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
