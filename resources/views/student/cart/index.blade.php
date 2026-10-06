@extends('layouts.student')

@section('title', 'My Cart')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="mb-4">

        <h3 class="mb-1">
            My Cart
        </h3>

        <p class="text-muted mb-0">
            Products you have added to your cart.
        </p>

    </div>


    {{-- Success Message --}}
    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Cart Items --}}
    @if ($cart->items->count())

        <div class="card shadow-sm border-0">

            <div class="card-body">

                @php
                    $cartTotal = 0;
                @endphp


                @foreach ($cart->items as $item)

                    @php
                        $itemTotal = $item->product->price * $item->quantity;
                        $cartTotal += $itemTotal;
                    @endphp


                    <div class="row align-items-center border-bottom py-3">

                        {{-- Image --}}
                        <div class="col-md-2">

                            @if ($item->product->image)

                                <img
                                    src="{{ asset('storage/' . $item->product->image) }}"
                                    alt="{{ $item->product->name }}"
                                    class="img-fluid rounded"
                                    style="height: 100px; width: 120px; object-fit: cover;"
                                >

                            @else

                                <div
                                    class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="height: 100px; width: 120px;"
                                >
                                    <i class="bi bi-image fs-3 text-muted"></i>
                                </div>

                            @endif

                        </div>


                        {{-- Product Details --}}
                        <div class="col-md-3">

                            <h5 class="mb-1">
                                {{ $item->product->name }}
                            </h5>

                            <span class="badge bg-primary">
                                {{ ucfirst($item->product->type) }}
                            </span>

                        </div>


                        {{-- Quantity --}}
                        <div class="col-md-3">

                            <small class="text-muted d-block mb-1">
                                Quantity
                            </small>

                            <div class="d-flex align-items-center gap-2">

                                {{-- Decrease --}}
                                @if ($item->quantity > 1)

                                    <form
                                        action="{{ route('student.cart.update', $item->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PUT')

                                        <input
                                            type="hidden"
                                            name="quantity"
                                            value="{{ $item->quantity - 1 }}"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-outline-secondary btn-sm"
                                        >
                                            <i class="bi bi-dash"></i>
                                        </button>

                                    </form>

                                @endif


                                {{-- Current Quantity --}}
                                <strong class="px-2">
                                    {{ $item->quantity }}
                                </strong>


                                {{-- Increase --}}
                                <form
                                    action="{{ route('student.cart.update', $item->id) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('PUT')

                                    <input
                                        type="hidden"
                                        name="quantity"
                                        value="{{ $item->quantity + 1 }}"
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-outline-secondary btn-sm"
                                    >
                                        <i class="bi bi-plus"></i>
                                    </button>

                                </form>

                            </div>

                        </div>


                        {{-- Price --}}
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Price
                            </small>

                            <strong>
                                ₹{{ number_format($item->product->price, 2) }}
                            </strong>

                        </div>


                        {{-- Total --}}
                        <div class="col-md-2">

                            <small class="text-muted d-block">
                                Total
                            </small>

                            <strong>
                                ₹{{ number_format($itemTotal, 2) }}
                            </strong>

                            {{-- Remove --}}
                            <form
                                action="{{ route('student.cart.remove', $item->id) }}"
                                method="POST"
                                class="mt-2"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                >
                                    <i class="bi bi-trash me-1"></i>
                                    Remove
                                </button>

                            </form>

                        </div>

                    </div>

                @endforeach


                {{-- Cart Total --}}
                <div class="d-flex justify-content-end mt-4">

                    <div class="text-end">

                        <p class="text-muted mb-1">
                            Cart Total
                        </p>

                        <h3 class="mb-3">
                            ₹{{ number_format($cartTotal, 2) }}
                        </h3>


                        <a
                            href="{{ route('student.products.index') }}"
                            class="btn btn-outline-primary"
                        >
                            <i class="bi bi-arrow-left me-1"></i>
                            Continue Shopping
                        </a>

                        {{-- Checkout will be added later --}}

                    </div>

                </div>

            </div>

        </div>

    @else

        {{-- Empty Cart --}}
        <div class="card shadow-sm border-0">

            <div class="card-body text-center py-5">

                <i class="bi bi-cart-x fs-1 text-muted"></i>

                <h4 class="mt-3">
                    Your cart is empty
                </h4>

                <p class="text-muted">
                    You haven't added any products yet.
                </p>

                <a
                    href="{{ route('student.products.index') }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-box-seam me-1"></i>
                    Browse Products
                </a>

            </div>

        </div>

    @endif

</div>

@endsection