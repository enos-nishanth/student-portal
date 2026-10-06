@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">Product Details</h3>
            <p class="text-muted mb-0">
                View product information
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.products.index') }}"
               class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <a href="{{ route('admin.products.edit', $product) }}"
               class="btn btn-warning">
                <i class="bi bi-pencil"></i>
                Edit
            </a>

        </div>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <div class="row">

                {{-- Product Image --}}
                <div class="col-md-5 text-center">

                    @if($product->image)

                        <img
                            src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}"
                            class="img-fluid rounded"
                            style="max-height: 400px;"
                        >

                    @else

                        <div
                            class="bg-light rounded d-flex align-items-center justify-content-center"
                            style="height: 350px;"
                        >
                            <i class="bi bi-image fs-1 text-muted"></i>
                        </div>

                    @endif

                </div>


                {{-- Product Information --}}
                <div class="col-md-7">

                    <h2 class="mb-3">
                        {{ $product->name }}
                    </h2>

                    <div class="mb-3">

                        <span class="badge bg-secondary">
                            {{ ucfirst($product->type) }}
                        </span>

                        @if($product->status)

                            <span class="badge bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Inactive
                            </span>

                        @endif

                    </div>


                    <h4 class="text-primary mb-4">
                        ₹{{ number_format($product->price, 2) }}
                    </h4>


                    <h5>Description</h5>

                    <p class="text-muted">
                        {{ $product->description ?? 'No description available.' }}
                    </p>


                    <hr>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection