@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    {{-- Welcome --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                Welcome back, {{ auth()->user()->name ?? 'Student' }}
            </h3>

            <p class="text-muted mb-0">
                Explore our latest products and courses.
            </p>
        </div>


        {{-- Cart --}}
        <a
            href="{{ route('student.cart.index') }}"
            class="position-relative text-decoration-none"
        >

            <i class="bi bi-cart3 fs-3 text-dark"></i>

            @if($cartItemCount > 0)
                <span
                    class="position-absolute top-0 start-100 translate-middle
                        badge rounded-pill bg-primary"
                >
                    {{ $cartItemCount }}
                </span>
            @endif

        </a>

    </div>


    {{-- Latest Products --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="mb-0">
            Latest Products
        </h5>

        <a
            href="{{ route('student.products.index') }}"
            class="btn btn-sm btn-outline-primary"
        >
            View All
        </a>

    </div>


    <div class="row g-4">

        @forelse($products as $product)

            <div class="col-md-4 col-lg-3">

                <div class="card h-100 shadow-sm">

                    {{-- Product Image --}}
                    @if($product->image)

                        <img
                            src="{{ asset('storage/' . $product->image) }}"
                            class="card-img-top"
                            alt="{{ $product->name }}"
                            style="height: 180px; object-fit: cover;"
                        >

                    @else

                        <div
                            class="bg-light d-flex align-items-center justify-content-center"
                            style="height: 180px;"
                        >
                            <i class="bi bi-image fs-1 text-muted"></i>
                        </div>

                    @endif


                    <div class="card-body d-flex flex-column">

                        {{-- Product Name --}}
                        <h6 class="card-title">
                            {{ $product->name }}
                        </h6>


                        {{-- Type --}}
                        <span class="badge bg-primary align-self-start mb-2">
                            {{ ucfirst($product->type) }}
                        </span>


                        {{-- Description --}}
                        <p class="card-text text-muted small">
                            {{ Str::limit($product->description, 70) }}
                        </p>


                        {{-- Price --}}
                        <h6 class="mb-3">
                            ₹{{ number_format($product->price, 2) }}
                        </h6>


                        {{-- View --}}
                        <a
                            href="{{ route('student.products.show', $product) }}"
                            class="btn btn-primary btn-sm mt-auto"
                        >
                            View Product
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="alert alert-light border text-center">

                    <i class="bi bi-box-seam fs-3 d-block mb-2"></i>

                    No products available yet.

                </div>

            </div>

        @endforelse

    </div>

</div>

@endsection