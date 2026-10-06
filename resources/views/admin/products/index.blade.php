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
                                                onsubmit="return confirm('Are you sure you want to delete this product?')"
                                          >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                      type="submit"
                                                      class="btn btn-sm btn-outline-danger"
                                                      title="Delete"
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

        </div>

    </div>

</div>

@endsection