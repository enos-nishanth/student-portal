@extends('layouts.student')

@section('title', 'Payment')

@section('content')

<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow-sm border-0">

                <div class="card-body p-4">

                    <h4 class="mb-4">
                        <i class="bi bi-credit-card me-2"></i>
                        Complete Payment
                    </h4>

                    <div class="mb-3">
                        <strong>Order Number:</strong>
                        {{ $order->order_number }}
                    </div>

                    <div class="mb-3">
                        <strong>Payment Method:</strong>
                        {{ strtoupper($order->payment_method) }}
                    </div>

                    <hr>

                    <h6 class="mb-3">Order Items</h6>

                    @foreach ($order->items as $item)

                        <div class="d-flex justify-content-between mb-2">

                            <div>
                                {{ $item->product_name }}

                                <small class="text-muted">
                                    × {{ $item->quantity }}
                                </small>
                            </div>

                            <div>
                                ₹{{ number_format($item->total, 2) }}
                            </div>

                        </div>

                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between fs-5 fw-bold">

                        <span>Total</span>

                        <span>
                            ₹{{ number_format($order->total_amount, 2) }}
                        </span>

                    </div>

                    <div class="mt-4">

                        <button
                            type="button"
                            class="btn btn-primary w-100"
                            id="pay-button"
                        >
                            <i class="bi bi-shield-check me-1"></i>
                            Pay Now
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
@push('scripts')

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

    document.getElementById('pay-button').addEventListener('click', async function () {

        const button = this;

        // Disable button while creating payment
        button.disabled = true;

        button.innerHTML = 'Processing...';

        try {

            // Create Razorpay Order
            const response = await fetch(
                "{{ route('student.payment.create-order') }}",
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },

                    body: JSON.stringify({
                        order_id: {{ $order->id }}
                    })
                }
            );

            const data = await response.json();

            if (!response.ok) {

                throw new Error(
                    data.message ||
                    'Unable to create payment order.'
                );
            }

            // Razorpay Checkout Options
            const options = {

                key: data.key,

                amount: data.amount,

                currency: data.currency,

                name: "Student Portal",

                description:
                    "Payment for {{ $order->order_number }}",

                order_id: data.razorpay_order_id,

                // Payment Successful
                handler: async function (paymentResponse) {

                    try {

                        console.log(
                            'Razorpay payment successful:',
                            paymentResponse
                        );

                        const verifyResponse = await fetch(
                            "{{ route('student.payment.verify') }}",
                            {
                                method: 'POST',

                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },

                                body: JSON.stringify({

                                    order_id: {{ $order->id }},

                                    razorpay_payment_id:
                                        paymentResponse.razorpay_payment_id,

                                    razorpay_order_id:
                                        paymentResponse.razorpay_order_id,

                                    razorpay_signature:
                                        paymentResponse.razorpay_signature

                                })
                            }
                        );

                        const verifyData =
                            await verifyResponse.json();


                        if (
                            !verifyResponse.ok ||
                            !verifyData.success
                        ) {

                            throw new Error(
                                verifyData.message ||
                                'Payment verification failed.'
                            );
                        }

                        //redirect to order
                        window.location.href =
                            verifyData.redirect;

                    } catch (error) {

                        console.error(
                            'Payment verification error:',
                            error
                        );

                        alert(
                            error.message ||
                            'Payment verification failed.'
                        );


                        // Allow retry
                        button.disabled = false;

                        button.innerHTML = `
                            <i class="bi bi-shield-check me-1"></i>
                            Pay Now
                        `;
                    }
                },

                modal: {

                    ondismiss: function () {

                        console.log(
                            'Razorpay checkout closed by user.'
                        );


                        // Allow retry
                        button.disabled = false;

                        button.innerHTML = `
                            <i class="bi bi-shield-check me-1"></i>
                            Pay Now
                        `;
                    }
                }
            };


            /*
            |--------------------------------------------------------------------------
            | Step 7: Open Razorpay
            |--------------------------------------------------------------------------
            */

            const razorpay = new Razorpay(options);
            // Payment Failed
            razorpay.on(
                'payment.failed',
                function (failureResponse) {

                    console.error(
                        'Razorpay payment failed:',
                        failureResponse
                    );


                    const description =
                        failureResponse.error &&
                        failureResponse.error.description
                            ? failureResponse.error.description
                            : 'Payment failed. Please try again.';


                    alert(description);


                    // Allow retry
                    button.disabled = false;

                    button.innerHTML = `
                        <i class="bi bi-shield-check me-1"></i>
                        Pay Now
                    `;
                }
            );

            //razorpay checkout
            razorpay.open();


        } catch (error) {


            console.error(
                'Payment error:',
                error
            );


            alert(
                error.message ||
                'Something went wrong. Please try again.'
            );


            // Allow retry
            button.disabled = false;

            button.innerHTML = `
                <i class="bi bi-shield-check me-1"></i>
                Pay Now
            `;
        }

    });

</script>

@endpush