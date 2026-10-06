@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">Create Product</h3>
            <p class="text-muted mb-0">Add a new product</p>
        </div>

        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>

    </div>


    {{-- Validation Summary --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="card shadow-sm">

        <div class="card-body">

            <form
                action="{{ route('admin.products.store') }}"
                method="POST"
                enctype="multipart/form-data"
                id="productForm"
                novalidate
            >

                @csrf


                <div class="row">

                    {{-- Product Name --}}
                    <div class="col-md-6 mb-3">

                        <label for="name" class="form-label">
                            Product Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter product name"
                            value="{{ old('name') }}"
                            required
                            minlength="3"
                            maxlength="255"
                            pattern="[A-Za-z0-9\s\-]+"
                        >

                        <div class="invalid-feedback">
                            Product name is required and must contain at least 3 characters.
                        </div>

                        @error('name')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Product Type --}}
                    <div class="col-md-6 mb-3">

                        <label for="type" class="form-label">
                            Product Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="type"
                            id="type"
                            class="form-select @error('type') is-invalid @enderror"
                            required
                        >

                            <option value="">Select Product Type</option>

                            <option value="digital"
                                {{ old('type') === 'digital' ? 'selected' : '' }}>
                                Digital Product
                            </option>

                            <option value="course"
                                {{ old('type') === 'course' ? 'selected' : '' }}>
                                Course
                            </option>

                            <option value="laptop"
                                {{ old('type') === 'laptop' ? 'selected' : '' }}>
                                Laptop
                            </option>

                            <option value="event"
                                {{ old('type') === 'event' ? 'selected' : '' }}>
                                Event
                            </option>

                        </select>

                        <div class="invalid-feedback">
                            Please select a product type.
                        </div>

                        @error('type')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Price --}}
                    <div class="col-md-6 mb-3">

                        <label for="price" class="form-label">
                            Price
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">₹</span>

                            <input
                                type="number"
                                name="price"
                                id="price"
                                class="form-control @error('price') is-invalid @enderror"
                                placeholder="0.00"
                                step="0.01"
                                min="0"
                                max="99999999.99"
                                value="{{ old('price') }}"
                                required
                            >

                            <div class="invalid-feedback">
                                Please enter a valid price greater than or equal to 0.
                            </div>

                        </div>

                        @error('price')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6 mb-3">

                        <label for="status" class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option value="1"
                                {{ old('status', '1') === '1' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ old('status') === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        <div class="invalid-feedback">
                            Please select a status.
                        </div>

                        @error('status')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Description --}}
                    <div class="col-12 mb-3">

                        <label for="description" class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="5"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Enter product description"
                            maxlength="2000"
                        >{{ old('description') }}</textarea>

                        <div class="form-text">
                            Maximum 2000 characters.
                        </div>

                        <div class="invalid-feedback">
                            Description cannot exceed 2000 characters.
                        </div>

                        @error('description')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Product Image --}}
                    <div class="col-md-6 mb-4">

                        <label for="image" class="form-label">
                            Product Image
                        </label>

                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="form-control @error('image') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <div class="form-text">
                            JPG, JPEG, PNG or WEBP. Maximum size: 2 MB.
                        </div>

                        <div class="invalid-feedback" id="imageError">
                            Please select a valid image.
                        </div>

                        @error('image')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="submitButton"
                    >
                        <i class="bi bi-check-lg"></i>
                        Create Product
                    </button>

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- Frontend Validation --}}
<script>

    const form = document.getElementById('productForm');

    const imageInput = document.getElementById('image');

    const imageError = document.getElementById('imageError');


    form.addEventListener('submit', function (event) {

        let isValid = true;


        // Browser Validation
        if (!form.checkValidity()) {

            isValid = false;

            event.preventDefault();

            event.stopPropagation();

        }


        //Image Validation
        if (imageInput.files.length > 0) {

            const file = imageInput.files[0];

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            const maxSize = 2 * 1024 * 1024;


            if (!allowedTypes.includes(file.type)) {

                isValid = false;

                event.preventDefault();

                imageInput.classList.add('is-invalid');

                imageError.textContent =
                    'Only JPG, JPEG, PNG and WEBP images are allowed.';

            }


            if (file.size > maxSize) {

                isValid = false;

                event.preventDefault();

                imageInput.classList.add('is-invalid');

                imageError.textContent =
                    'Image size must not exceed 2 MB.';

            }

        }

        form.classList.add('was-validated');

      //Prevent Double Submission
        if (isValid) {

            const submitButton =
                document.getElementById('submitButton');

            submitButton.disabled = true;

            submitButton.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1"></span> Creating...';

        }

    });


    //Remove Image Error When User Selects Valid Image
    imageInput.addEventListener('change', function () {

        imageInput.classList.remove('is-invalid');

        imageError.textContent =
            'Please select a valid image.';

    });

</script>

@endsection