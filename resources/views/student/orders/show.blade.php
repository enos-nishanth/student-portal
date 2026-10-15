@extends('layouts.student')

@section('title', 'Order Details')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>
    </div>
@endif

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
    
@if (
    $order->order_status !== 'cancelled' &&
    !in_array($order->order_status, [
        'shipped',
        'out_for_delivery',
        'delivered',
    ]) &&
    $order->created_at->copy()->addHours(24)->isFuture()
)
    <form
        action="{{ route('student.orders.cancel', $order) }}"
        method="POST"
        id="cancelOrderForm"
    >
        @csrf
        @method('PATCH')

        <button
            type="button"
            class="btn btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#cancelOrderModal"
        >
            <i class="bi bi-x-circle me-1"></i>
            Cancel Order
        </button>
    </form>
@endif

@if (
    $order->order_status === 'cancelled' &&
    in_array($order->payment_method, ['upi', 'card']) &&
    $order->payment_status === 'paid' &&
    !in_array($order->refund_status, ['pending', 'processed'])
)
    <form
        action="{{ route('student.orders.refund', $order) }}"
        method="POST"
        id="refundForm"
    >
        @csrf

        <button
            type="button"
            class="btn btn-warning"
            data-bs-toggle="modal"
            data-bs-target="#refundModal"
        >
            <i class="bi bi-arrow-counterclockwise me-1"></i>
            Request Refund
        </button>
    </form>
@endif


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

<div class="modal fade" id="refundModal" tabindex="-1"
     aria-labelledby="refundModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="refundModalLabel">
                    <i class="bi bi-wallet2 text-warning me-2"></i>
                    Request a Refund
                </h5>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted">
                    Are you sure you want to request a refund
                    for this cancelled order?
                </p>

                <div class="bg-light rounded-3 p-3 mb-3">
                    <small class="text-muted">Refund amount</small>
                    <h3 class="fw-bold mb-0">
                        ₹{{ number_format((float) $order->total_amount, 2) }}
                    </h3>
                </div>

                <div class="alert alert-warning mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    The refund is subject to Razorpay processing
                    and confirmation.
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light border"
                        data-bs-dismiss="modal">
                    Not Now
                </button>

                <button type="button" class="btn btn-warning"
                        id="confirmRefundBtn">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Confirm Refund
                </button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="cancelOrderModal" tabindex="-1"
     aria-labelledby="cancelOrderModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="cancelOrderModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Cancel Order?
                </h5>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted">
                    Are you sure you want to cancel this order?
                    This action cannot be undone.
                </p>

                <div class="bg-light rounded-3 p-3 mb-3">
                    <small class="text-muted">Order Number</small>
                    <div class="fw-semibold">
                        {{ $order->order_number }}
                    </div>

                    <small class="text-muted d-block mt-2">
                        Order Amount
                    </small>
                    <h4 class="fw-bold mb-0">
                        ₹{{ number_format((float) $order->total_amount, 2) }}
                    </h4>
                </div>

                @if (
                    in_array($order->payment_method, ['upi', 'card']) &&
                    $order->payment_status === 'paid'
                )
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Your online payment may require a separate refund
                        request. Cancellation does not guarantee an immediate refund.
                    </div>
                @endif
            </div>

            <div class="modal-footer border-0">
                <button type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal">
                    Keep Order
                </button>

                <button type="button"
                        class="btn btn-danger"
                        id="confirmCancelBtn">
                    <i class="bi bi-x-circle me-1"></i>
                    Confirm Cancellation
                </button>
            </div>

        </div>
    </div>
</div>


@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const confirmButton = document.getElementById('confirmRefundBtn');
            const refundForm = document.getElementById('refundForm');

            if (confirmButton && refundForm) {
                confirmButton.addEventListener('click', function () {
                    refundForm.submit();
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cancelButton = document.getElementById('confirmCancelBtn');
            const cancelForm = document.getElementById('cancelOrderForm');

            if (cancelButton && cancelForm) {
                cancelButton.addEventListener('click', function () {
                    cancelButton.disabled = true;
                    cancelButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1"></span> Cancelling...';

                    cancelForm.submit();
                });
            }
        });
    </script>
@endpush
