@extends('layouts.student')

@section('title', 'Products')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="mb-4">
        <h3 class="mb-1">
            Products
        </h3>

        <p class="text-muted mb-0">
            Explore our available products.
        </p>
    </div>


    {{-- Products --}}
    <div class="row g-4">

        @forelse ($products as $product)

            <div class="col-md-6 col-lg-4">

                <div class="card h-100 shadow-sm">

                    {{-- Product Image --}}
                    @if ($product->image)

                        <img
                            src="{{ asset('storage/' . $product->image) }}"
                            class="card-img-top"
                            alt="{{ $product->name }}"
                            style="height: 220px; object-fit: cover;"
                        >

                    @else

                        <div
                            class="bg-light d-flex align-items-center justify-content-center"
                            style="height: 220px;"
                        >
                            <i class="bi bi-image fs-1 text-muted"></i>
                        </div>

                    @endif


                    <div class="card-body d-flex flex-column">

                        {{-- Product Name --}}
                        <h5 class="card-title mb-2">
                            {{ $product->name }}
                        </h5>


                        {{-- Product Type --}}
                        <span class="badge bg-primary align-self-start mb-2">
                            {{ ucfirst($product->type) }}
                        </span>


                        {{-- Description --}}
                        <p class="card-text text-muted">
                            {{ Str::limit($product->description, 100) }}
                        </p>


                        {{-- Price --}}
                        <h5 class="mb-3">
                            ₹{{ number_format($product->price, 2) }}
                        </h5>


                        {{-- View Product --}}
                        <a
                            href="{{ route('student.products.show', $product) }}"
                            class="btn btn-primary mt-auto"
                        >
                            <i class="bi bi-eye me-1"></i>
                            View Product
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="alert alert-info">
                    No products are currently available.
                </div>

            </div>

        @endforelse

    </div>

</div>

@endsection