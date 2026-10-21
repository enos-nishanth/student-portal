@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Products</h3>
            <p class="text-muted mb-0">Manage all products</p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Add Product
        </a>
    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            @if($products->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($products as $product)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        @if($product->image)
                                            <img
                                                src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                width="60"
                                                height="60"
                                                class="rounded object-fit-cover"
                                            >
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="width:60px;height:60px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                    </td>

                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($product->type) }}
                                        </span>
                                    </td>

                                    <td>
                                        ₹{{ number_format($product->price, 2) }}
                                    </td>

                                    <td>
                                        @if($product->status)
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td>

                                          {{-- View --}}
                                          <a
                                                href="{{ route('admin.products.show', $product) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View"
                                          >
                                                <i class="bi bi-eye"></i>
                                          </a>


                                          {{-- Edit --}}
                                          <a
                                                href="{{ route('admin.products.edit', $product) }}"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Edit"
                                          >
                                                <i class="bi bi-pencil"></i>
                                          </a>


                                          {{-- Delete --}}
                                          
                                        <form
                                            action="{{ route('admin.products.destroy', $product) }}"
                                            method="POST"
                                            class="d-inline"
                                            id="deleteProductForm-{{ $product->id }}"
                                        >
                                        @csrf
                                        @method('DELETE')

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteProductModal"
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>


                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-box-seam fs-1 text-muted"></i>

                    <h5 class="mt-3">No Products Found</h5>

                    <p class="text-muted">
                        You haven't created any products yet.
                    </p>

                    <a href="{{ route('admin.products.create') }}"
                       class="btn btn-primary">
                        Add Your First Product
                    </a>

                </div>

            @endif
            {{-- Pagination --}}
            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

    </div>

</div>


<div class="modal fade" id="deleteProductModal" tabindex="-1"
     aria-labelledby="deleteProductModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="deleteProductModalLabel">
                    <i class="bi bi-trash3 text-danger me-2"></i>
                    Delete Product?
                </h5>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted">
                    Are you sure you want to delete this product?
                    This action may not be reversible.
                </p>

                <div class="bg-light rounded-3 p-3">
                    <small class="text-muted">Product name</small>
                    <div class="fw-semibold" id="deleteProductName">
                        {{ $product->name }}
                    </div>
                </div>

                <div class="alert alert-warning mt-3 mb-0">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Please make sure this is the correct product before deleting it.
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light border"
                        data-bs-dismiss="modal">
                    Keep Product
                </button>

                <button type="button" class="btn btn-danger"
                        id="confirmDeleteProductBtn">
                    <i class="bi bi-trash me-1"></i>
                    Delete Product
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('deleteProductModal');
            const confirmButton = document.getElementById('confirmDeleteProductBtn');
            const productNameElement = document.getElementById('deleteProductName');

            let selectedProductForm = null;

            if (!deleteModal || !confirmButton || !productNameElement) {
                return;
            }

            deleteModal.addEventListener('show.bs.modal', function (event) {
                const triggerButton = event.relatedTarget;

                if (!triggerButton) {
                    selectedProductForm = null;
                    return;
                }

                const productId = triggerButton.getAttribute('data-product-id');
                const productName = triggerButton.getAttribute('data-product-name');

                selectedProductForm = document.getElementById(
                    'deleteProductForm-' + productId
                );

                productNameElement.textContent = productName || 'Selected product';

                confirmButton.disabled = false;
                confirmButton.innerHTML =
                    '<i class="bi bi-trash me-1"></i> Delete Product';
            });

            confirmButton.addEventListener('click', function () {
                if (!selectedProductForm) {
                    return;
                }

                confirmButton.disabled = true;
                confirmButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> Deleting...';

                selectedProductForm.submit();
            });
        });
    </script>
@endpush
