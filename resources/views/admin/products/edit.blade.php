@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Edit Product</h3>
            <p class="text-muted mb-0">Update product information</p>
        </div>

        <a href="{{ route('admin.products.index') }}"
           class="btn btn-secondary">
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
                action="{{ route('admin.products.update', $product) }}"
                method="POST"
                enctype="multipart/form-data"
                id="productForm"
                novalidate
            >
                @csrf
                @method('PUT')

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
                            value="{{ old('name', $product->name) }}"
                            minlength="3"
                            maxlength="255"
                            required
                        >

                        <div class="invalid-feedback">
                            Please enter a product name between 3 and 255 characters.
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
                                {{ old('type', $product->type) === 'digital' ? 'selected' : '' }}>
                                Digital Product
                            </option>

                            <option value="course"
                                {{ old('type', $product->type) === 'course' ? 'selected' : '' }}>
                                Course
                            </option>

                            <option value="laptop"
                                {{ old('type', $product->type) === 'laptop' ? 'selected' : '' }}>
                                Laptop
                            </option>

                            <option value="event"
                                {{ old('type', $product->type) === 'event' ? 'selected' : '' }}>
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
                            Price <span class="text-danger">*</span>
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
                                value="{{ old('price', $product->price) }}"
                                required
                            >

                            <div class="invalid-feedback">
                                Enter a valid price between 0 and 99,999,999.99.
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
                            Status <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >
                            <option value="1"
                                {{ (string) old('status', $product->status) === '1' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ (string) old('status', $product->status) === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>

                        <div class="invalid-feedback">
                            Please select a valid status.
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
                        >{{ old('description', $product->description) }}</textarea>

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

                    {{-- Current Product Image --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Current Product Image
                        </label>

                        <div>
                            @if ($product->image)
                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    width="180"
                                    height="180"
                                    class="rounded object-fit-cover border"
                                >
                            @else
                                <div
                                    class="bg-light rounded border d-flex align-items-center justify-content-center"
                                    style="width: 180px; height: 180px;"
                                >
                                    <i class="bi bi-image fs-1 text-muted"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Replace Product Image --}}
                    <div class="col-md-6 mb-4">
                        <label for="image" class="form-label">
                            Replace Product Image
                        </label>

                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="form-control @error('image') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <div class="form-text">
                            Leave empty to keep the current image.
                            <br>
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
                        Update Product
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
@endsection

{{-- Frontend Validation --}}
@push('scripts')
<script>
(() => {
    const form = document.getElementById('productForm');

    if (!form) return;

    const nameInput = document.getElementById('name');
    const typeInput = document.getElementById('type');
    const priceInput = document.getElementById('price');
    const statusInput = document.getElementById('status');
    const descriptionInput = document.getElementById('description');
    const imageInput = document.getElementById('image');
    const imageError = document.getElementById('imageError');
    const submitButton = document.getElementById('submitButton');

    const maxImageSize = 2 * 1024 * 1024;

    const allowedImageTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    function showError(input, message) {
        input.setCustomValidity(message);
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');

        const feedback = input.id === 'image'
            ? imageError
            : input.closest('.input-group')?.querySelector('.invalid-feedback')
                || input.parentElement.querySelector('.invalid-feedback');

        if (feedback) {
            feedback.textContent = message;
        }
    }

    function clearError(input) {
        input.setCustomValidity('');
        input.classList.remove('is-invalid', 'is-valid');

        if (input.id === 'image' && imageError) {
            imageError.textContent = 'Please select a valid image.';
        }
    }

    // Product Name
    function validateName() {
        const value = nameInput.value.trim();

        if (!value) {
            showError(nameInput, 'Product name is required.');
            return false;
        }

        if (value.length < 3 || value.length > 255) {
            showError(
                nameInput,
                'Product name must be between 3 and 255 characters.'
            );
            return false;
        }

        if (!/^[A-Za-z0-9\s-]+$/.test(value)) {
            showError(
                nameInput,
                'Use only letters, numbers, spaces, and hyphens.'
            );
            return false;
        }

        clearError(nameInput);
        return true;
    }

    // Product Type
    function validateType() {
        const allowedTypes = [
            'digital',
            'course',
            'laptop',
            'event'
        ];

        if (!allowedTypes.includes(typeInput.value)) {
            showError(typeInput, 'Please select a valid product type.');
            return false;
        }

        clearError(typeInput);
        return true;
    }

    // Price
    function validatePrice() {
        const value = priceInput.value.trim();

        if (!value) {
            showError(priceInput, 'Price is required.');
            return false;
        }

        const amount = Number(value);

        if (
            !Number.isFinite(amount) ||
            amount < 0 ||
            amount > 99999999.99
        ) {
            showError(
                priceInput,
                'Enter a price between 0 and 99,999,999.99.'
            );
            return false;
        }

        if (!/^\d+(\.\d{1,2})?$/.test(value)) {
            showError(
                priceInput,
                'Price can have up to 2 decimal places.'
            );
            return false;
        }

        clearError(priceInput);
        return true;
    }

    // Status
    function validateStatus() {
        if (!['0', '1'].includes(statusInput.value)) {
            showError(statusInput, 'Please select a valid status.');
            return false;
        }

        clearError(statusInput);
        return true;
    }

    // Description
    function validateDescription() {
        if (descriptionInput.value.length > 2000) {
            showError(
                descriptionInput,
                'Description cannot exceed 2000 characters.'
            );
            return false;
        }

        clearError(descriptionInput);
        return true;
    }

    // Product Image
    function validateImage() {
        const file = imageInput.files?.[0];

        // Image is optional when editing a product.
        if (!file) {
            clearError(imageInput);
            return true;
        }

        const extensionAllowed =
            /\.(jpe?g|png|webp)$/i.test(file.name);

        const typeAllowed =
            allowedImageTypes.includes(file.type);

        if (!extensionAllowed || !typeAllowed) {
            showError(
                imageInput,
                'Only JPG, JPEG, PNG, and WEBP images are allowed.'
            );
            return false;
        }

        if (file.size > maxImageSize) {
            showError(
                imageInput,
                'Image size must not exceed 2 MB.'
            );
            return false;
        }

        clearError(imageInput);
        return true;
    }

    const validators = [
        [nameInput, validateName],
        [typeInput, validateType],
        [priceInput, validatePrice],
        [statusInput, validateStatus],
        [descriptionInput, validateDescription],
        [imageInput, validateImage]
    ];

    // Validate when the user leaves a field.
    // Validate again while typing or changing selections.
    validators.forEach(([input, validator]) => {
        input.addEventListener('blur', validator);

        input.addEventListener('input', validator);

        input.addEventListener('change', validator);
    });

    // Validate every field before submitting.
    form.addEventListener('submit', function (event) {
        const results = validators.map(
            ([input, validator]) => validator()
        );

        const isFormValid = results.every(Boolean);

        if (!isFormValid) {
            event.preventDefault();
            event.stopPropagation();

            const firstInvalid = form.querySelector('.is-invalid');

            if (firstInvalid) {
                firstInvalid.focus();
            }

            return;
        }

        // Prevent double submission.
        if (submitButton) {
            submitButton.disabled = true;

            submitButton.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm me-1"
                    aria-hidden="true"
                ></span>
                Updating...
            `;
        }
    });
})();
</script>
@endpush