@extends('layouts.student')

@section('title', 'Checkout')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="mb-4">
        <h3 class="mb-1">Checkout</h3>

        <p class="text-muted mb-0">
            Review your order and select a delivery address.
        </p>
    </div>


    {{-- Checkout Form --}}
    <form
        action="{{ route('student.checkout.place') }}"
        method="POST"
    >

        @csrf

        @php
            $selectedAddressId =
                optional($addresses->firstWhere('is_default', true))->id
                ?? optional($addresses->first())->id;

            $cartTotal = 0;
        @endphp


        <div class="row g-4">

            {{-- LEFT SIDE --}}           
            <div class="col-lg-8">


                {{-- SELECT DELIVERY ADDRESS  --}}

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-body">

                        <h5 class="mb-3">
                            <i class="bi bi-geo-alt me-1"></i>
                            Delivery Address
                        </h5>


                        @if ($addresses->count())

                            @foreach ($addresses as $address)

                                <div class="border rounded p-3 mb-3">

                                    <div class="d-flex justify-content-between">

                                        <div>

                                            <h6 class="mb-2">

                                                @if ($address->type === 'home')

                                                    <i class="bi bi-house-door me-1"></i>
                                                    Home

                                                @elseif ($address->type === 'office')

                                                    <i class="bi bi-building me-1"></i>
                                                    Office

                                                @else

                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    Other

                                                @endif


                                                @if ($address->is_default)

                                                    <span class="badge bg-success ms-2">
                                                        Default
                                                    </span>

                                                @endif

                                            </h6>


                                            <p class="mb-1">
                                                {{ $address->address }}
                                            </p>


                                            @if ($address->town)

                                                <p class="text-muted mb-1">
                                                    {{ $address->town }}
                                                </p>

                                            @endif


                                            <p class="text-muted mb-0">

                                                {{ $address->city }}

                                                -

                                                {{ $address->pincode }}

                                            </p>

                                        </div>


                                        {{-- Address Radio --}}

                                        <div>

                                            <input
                                                type="radio"
                                                name="delivery_address_id"
                                                value="{{ $address->id }}"
                                                class="form-check-input"
                                                {{ $selectedAddressId == $address->id ? 'checked' : '' }}
                                            >

                                        </div>

                                    </div>

                                </div>

                            @endforeach


                            {{-- Add New Address --}}

                            <a
                                href="{{ route('student.addresses.create') }}"
                                class="btn btn-outline-primary"
                            >

                                <i class="bi bi-plus-lg me-1"></i>

                                Add New Address

                            </a>


                        @else


                            {{-- No Address --}}

                            <div class="text-center py-4">

                                <i class="bi bi-geo-alt fs-2 text-muted"></i>

                                <p class="text-muted mt-2 mb-3">
                                    No delivery address found.
                                </p>


                                <a
                                    href="{{ route('student.addresses.create') }}"
                                    class="btn btn-primary"
                                >

                                    <i class="bi bi-plus-lg me-1"></i>

                                    Add Delivery Address

                                </a>

                            </div>


                        @endif


                        {{-- Validation Error --}}

                        @error('delivery_address_id')

                            <div class="alert alert-danger mt-3 mb-0">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>



                {{--  ORDER ITEMS  --}}

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <h5 class="mb-3">

                            <i class="bi bi-cart me-1"></i>

                            Order Summary

                        </h5>


                        @foreach ($items as $item)

                            @php

                                $itemTotal =
                                    $item->product->price *
                                    $item->quantity;

                                $cartTotal += $itemTotal;

                            @endphp


                            <div
                                class="d-flex align-items-center border-bottom py-3"
                            >


                                {{-- Product Image --}}

                                <div class="me-3">

                                    @if ($item->product->image)

                                        <img
                                            src="{{ asset('storage/' . $item->product->image) }}"
                                            alt="{{ $item->product->name }}"
                                            class="rounded"
                                            style="
                                                height: 70px;
                                                width: 80px;
                                                object-fit: cover;
                                            "
                                        >

                                    @else

                                        <div
                                            class="
                                                bg-light
                                                rounded
                                                d-flex
                                                align-items-center
                                                justify-content-center
                                            "
                                            style="
                                                height: 70px;
                                                width: 80px;
                                            "
                                        >

                                            <i
                                                class="
                                                    bi
                                                    bi-image
                                                    fs-4
                                                    text-muted
                                                "
                                            ></i>

                                        </div>

                                    @endif

                                </div>



                                {{-- Product Details --}}

                                <div class="flex-grow-1">

                                    <h6 class="mb-1">

                                        {{ $item->product->name }}

                                    </h6>


                                    <small class="text-muted">

                                        ₹{{ number_format($item->product->price, 2) }}

                                        ×

                                        {{ $item->quantity }}

                                    </small>

                                </div>



                                {{-- Item Total --}}

                                <strong>

                                    ₹{{ number_format($itemTotal, 2) }}

                                </strong>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

            {{-- RIGHT SIDE --}}

            <div class="col-lg-4">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <h5 class="mb-3">
                            Order Total
                        </h5>


                        {{-- Subtotal --}}

                        <div
                            class="
                                d-flex
                                justify-content-between
                                mb-2
                            "
                        >

                            <span class="text-muted">
                                Subtotal
                            </span>

                            <span>
                                ₹{{ number_format($cartTotal, 2) }}
                            </span>

                        </div>


                        <hr>


                        {{-- Total --}}

                        <div
                            class="
                                d-flex
                                justify-content-between
                                mb-4
                            "
                        >

                            <strong>
                                Total
                            </strong>

                            <strong class="fs-5">

                                ₹{{ number_format($cartTotal, 2) }}

                            </strong>

                        </div>

                        {{-- Payment Method --}}
                        <div class="mb-4">

                              <h6 class="mb-3">
                                    Payment Method
                              </h6>

                              <div class="border rounded p-3 mb-2">

                                      <div class="form-check">

                                          <input
                                          class="form-check-input"
                                          type="radio"
                                          name="payment_method"
                                          id="payment_upi"
                                          value="upi"
                                          checked
                                          >

                                          <label
                                          class="form-check-label"
                                          for="payment_upi"
                                          >
                                          <i class="bi bi-phone me-1"></i>
                                          UPI
                                          </label>

                                    </div>

                              </div>


                              <div class="border rounded p-3 mb-2">

                                    <div class="form-check">

                                          <input
                                          class="form-check-input"
                                          type="radio"
                                          name="payment_method"
                                          id="payment_card"
                                          value="card"
                                          >

                                          <label
                                          class="form-check-label"
                                          for="payment_card"
                                          >
                                          <i class="bi bi-credit-card me-1"></i>
                                          Card
                                          </label>

                                    </div>

                              </div>


                              <div class="border rounded p-3">

                                    <div class="form-check">

                                          <input
                                          class="form-check-input"
                                          type="radio"
                                          name="payment_method"
                                          id="payment_cod"
                                          value="cod"
                                          >

                                          <label
                                          class="form-check-label"
                                          for="payment_cod"
                                          >
                                          <i class="bi bi-cash-stack me-1"></i>
                                          Cash on Delivery
                                          </label>

                                    </div>

                              </div>

                        </div>                              


                        {{-- Proceed to Payment --}}

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                            {{ $addresses->isEmpty() ? 'disabled' : '' }}
                        >

                            <i class="bi bi-credit-card me-1"></i>

                            Proceed to Payment

                        </button>


                        {{-- Back to Cart --}}

                        <a
                            href="{{ route('student.cart.index') }}"
                            class="
                                btn
                                btn-outline-secondary
                                w-100
                                mt-2
                            "
                        >

                            Back to Cart

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection