@extends('layouts.student')

@section('title', 'My Addresses')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">My Addresses</h3>
            <p class="text-muted mb-0">
                Manage your saved delivery addresses.
            </p>
        </div>

        <a
            href="{{ route('student.addresses.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Add New Address
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    @if($addresses->count())

        <div class="row">

            @foreach($addresses as $address)

                <div class="col-md-6 col-lg-4 mb-4">

                    <div class="card shadow-sm border-0 h-100">

                        <div class="card-body">

                            {{-- Address Header --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">

                                <div>

                                    <h5 class="mb-1">

                                        @if($address->type === 'home')

                                            <i class="bi bi-house-door me-1"></i>
                                            Home

                                        @elseif($address->type === 'office')

                                            <i class="bi bi-building me-1"></i>
                                            Office

                                        @else

                                            <i class="bi bi-geo-alt me-1"></i>
                                            Other

                                        @endif

                                    </h5>

                                </div>


                                @if($address->is_default)

                                    <span class="badge bg-success">
                                        Default
                                    </span>

                                @endif

                            </div>


                            {{-- Address --}}
                            <p class="mb-2">
                                {{ $address->address }}
                            </p>


                            {{-- City + Pincode --}}
                            <p class="text-muted mb-3">

                                {{ $address->city }}
                                -
                                {{ $address->pincode }}

                            </p>


                            {{-- Coordinates --}}
                            @if($address->latitude && $address->longitude)

                                <small class="text-muted d-block mb-3">

                                    <i class="bi bi-geo-alt me-1"></i>

                                    {{ $address->latitude }},
                                    {{ $address->longitude }}

                                </small>

                            @endif


                            {{-- Actions --}}
                            <div class="d-flex gap-2">

                                <a
                                    href="{{ route('student.addresses.edit', $address) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="bi bi-pencil me-1"></i>
                                    Edit
                                </a>


                                <form
                                    action="{{ route('student.addresses.destroy', $address) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this address?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                    >
                                        <i class="bi bi-trash me-1"></i>
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        {{-- Empty State --}}
        <div class="card shadow-sm border-0">

            <div class="card-body text-center py-5">

                <i
                    class="bi bi-geo-alt"
                    style="font-size: 3rem;"
                ></i>

                <h4 class="mt-3">
                    No Saved Addresses
                </h4>

                <p class="text-muted">
                    Add a delivery address to use during checkout.
                </p>

                <a
                    href="{{ route('student.addresses.create') }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Address
                </a>

            </div>

        </div>

    @endif

</div>

@endsection