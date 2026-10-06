@extends('layouts.student')

@section('title', $product->name)

@section('content')

<div class="container-fluid">

    {{-- Back --}}
    <div class="mb-4">

        <a
            href="{{ route('student.products.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Products
        </a>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="row g-4">

                {{-- Product Image --}}
                <div class="col-md-6">

                    @if ($product->image)

                        <img
                            src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}"
                            class="img-fluid rounded"
                            style="width: 100%; height: 400px; object-fit: cover;"
                        >

                    @else

                        <div
                            class="bg-light rounded d-flex align-items-center justify-content-center"
                            style="height: 400px;"
                        >
                            <i class="bi bi-image fs-1 text-muted"></i>
                        </div>

                    @endif

                </div>


                {{-- Product Details --}}
                <div class="col-md-6">

                    {{-- Type --}}
                    <span class="badge bg-primary mb-3">
                        {{ ucfirst($product->type) }}
                    </span>


                    {{-- Name --}}
                    <h2 class="mb-3">
                        {{ $product->name }}
                    </h2>


                    {{-- Price --}}
                    <h3 class="text-primary mb-4">
                        ₹{{ number_format($product->price, 2) }}
                    </h3>


                    {{-- Description --}}
                    <div class="mb-4">

                        <h5>
                            Description
                        </h5>

                        <p class="text-muted">
                            {{ $product->description }}
                        </p>

                    </div>


                    {{-- Add To Cart --}}
                    <form action="{{ route('student.cart.add', $product) }}" method="POST">
                        @csrf

                        {{-- Quantity --}}
                        <div class="mb-3">

                            <label for="quantity" class="form-label">
                                Quantity
                                
                            </label>

                            <div class="input-group" style="max-width: 180px;">

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="decreaseQuantity()"
                                >
                                    −
                                </button>

                                <input
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    class="form-control text-center"
                                    value="1"
                                    min="1"
                                    step="1"
                                    required
                                >

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="increaseQuantity()"
                                >
                                    +
                                </button>

                            </div>

                            <div
                                id="quantityError"
                                class="text-danger small mt-1"
                                style="display: none;"
                            >
                                Quantity must be at least 1.
                            </div>

                        </div>

                    <button
                        type="submit"
                        class="btn btn-primary btn-lg"
                    >
                        <i class="bi bi-cart-plus me-1"></i>
                        Add to Cart
                    </button>

                    {{-- Buy Now --}}
                    <button
                        type="submit"
                        formaction="{{ route('student.buy-now', $product) }}"
                        class="btn btn-success btn-lg"
                    >
                        <i class="bi bi-lightning-charge-fill me-1"></i>
                        Buy Now
                    </button>
                </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
@push('scripts')
<script>

    function increaseQuantity() {

        let quantity = document.getElementById('quantity');

        let currentValue = parseInt(quantity.value) || 1;

        quantity.value = currentValue + 1;

    }


    function decreaseQuantity() {

        let quantity = document.getElementById('quantity');

        let currentValue = parseInt(quantity.value) || 1;

        if (currentValue > 1) {
            quantity.value = currentValue - 1;
        } else {
            quantity.value = 1;
        }

    }


    // Frontend validation before submitting
    document.querySelector('form').addEventListener('submit', function(event) {

        let quantity = document.getElementById('quantity');

        let quantityError = document.getElementById('quantityError');

        let value = parseInt(quantity.value);

        if (isNaN(value) || value < 1) {

            event.preventDefault();

            quantity.classList.add('is-invalid');

            quantityError.style.display = 'block';

            quantity.value = 1;

            return;
        }

        quantity.classList.remove('is-invalid');

        quantityError.style.display = 'none';

    });


    // Remove validation error when user changes quantity
    document.getElementById('quantity').addEventListener('input', function() {

        let value = parseInt(this.value);

        if (!isNaN(value) && value >= 1) {

            this.classList.remove('is-invalid');

            document.getElementById('quantityError').style.display = 'none';

        }

    });

</script>
@endpush