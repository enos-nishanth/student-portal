@extends('layouts.student')

@section('title', 'My Purchases')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-bag-check me-2"></i>
                My Purchases
            </h3>

            <p class="text-muted mb-0">
                View your orders and purchase history
            </p>
        </div>

        <a href="{{ route('student.products.index') }}"
           class="btn btn-outline-primary">

            <i class="bi bi-shop me-1"></i>
            Continue Shopping

        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    @if($orders->isEmpty())

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <i class="bi bi-bag-x fs-1 text-muted"></i>

                <h5 class="mt-3">
                    No purchases yet
                </h5>

                <p class="text-muted">
                    You haven't placed any orders yet.
                </p>

                <a href="{{ route('student.products.index') }}"
                   class="btn btn-primary">

                    <i class="bi bi-shop me-1"></i>
                    Browse Products

                </a>

            </div>

        </div>

    @else

        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">
                                    Order
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Items
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Payment
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($orders as $order)
                            @if(
                              $order->payment_method === 'cod' ||
                              $order->payment_status === 'paid'
                              )

                                <tr>

                                    <td class="px-4">

                                        <strong>
                                            {{ $order->order_number }}
                                        </strong>

                                    </td>

                                    <td>

                                        {{ $order->created_at->timezone('Asia/Kolkata')->format('d M Y') }}

                                        <br>

                                        <small class="text-muted">
                                            {{ $order->created_at->timezone('Asia/Kolkata')->format('h:i A') }}  
                                        </small>

                                    </td>

                                    <td>

                                        {{ $order->items->sum('quantity') }}

                                        {{ $order->items->sum('quantity') == 1 ? 'item' : 'items' }}

                                    </td>

                                    <td>

                                        <strong>
                                            ₹{{ number_format($order->total_amount, 2) }}
                                        </strong>

                                    </td>

                                    <td>

                                        @if($order->payment_method === 'cod')

                                            <span class="badge bg-secondary">
                                                COD
                                            </span>

                                        @else

                                            <span class="badge bg-primary">
                                                {{ strtoupper($order->payment_method) }}
                                            </span>

                                        @endif

                                        <br>

                                        @if($order->payment_status === 'paid')

                                            <small class="text-success">
                                                Paid
                                            </small>

                                        @else

                                            <small class="text-warning">
                                                Pending
                                            </small>

                                        @endif

                                    </td>

                                    <td>

                                        @if($order->order_status === 'confirmed')

                                            <span class="badge bg-success">
                                                Confirmed
                                            </span>

                                        @elseif($order->order_status === 'processing')

                                            <span class="badge bg-info">
                                                Processing
                                            </span>

                                        @elseif($order->order_status === 'shipped')

                                            <span class="badge bg-primary">
                                                Shipped
                                            </span>

                                        @elseif($order->order_status === 'out_for_delivery')

                                            <span class="badge bg-warning text-dark">
                                                Out for Delivery
                                            </span>

                                        @elseif($order->order_status === 'delivered')

                                            <span class="badge bg-success">
                                                Delivered
                                            </span>

                                        @elseif($order->order_status === 'cancelled')

                                            <span class="badge bg-danger">
                                                Cancelled
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                {{ ucfirst($order->order_status) }}
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <a href="{{ route('student.orders.show', $order) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-eye me-1"></i>
                                            View

                                        </a>

                                    </td>

                                </tr>
                              @endif
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection