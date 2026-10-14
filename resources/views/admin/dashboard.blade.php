@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

{{-- Dashboard Header --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Dashboard</h2>
        <p class="text-muted mb-0">
            Welcome to your admin panel. Here's an overview of your portal.
        </p>
    </div>

    <span class="badge text-bg-primary px-3 py-2">
        <i class="bi bi-speedometer2 me-2"></i>Admin Overview
    </span>
</div>

{{-- Statistics Cards --}}
<div class="row g-4 mb-4">

    {{-- Students --}}
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Total Students</p>
                        <h2 class="fw-bold mb-0">{{ $studentCount }}</h2>
                    </div>

                    <div class="bg-primary-subtle text-primary rounded-3 p-3">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Products --}}
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Total Products</p>
                        <h2 class="fw-bold mb-0">{{ $productCount }}</h2>
                    </div>

                    <div class="bg-success-subtle text-success rounded-3 p-3">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders --}}
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Total Orders</p>
                        <h2 class="fw-bold mb-0">{{ $orderCount }}</h2>
                    </div>

                    <div class="bg-warning-subtle text-warning-emphasis rounded-3 p-3">
                        <i class="bi bi-cart-check fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Management Section --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-0 p-4">
        <h5 class="fw-bold mb-1">Quick Actions</h5>
        <p class="text-muted mb-0 small">
            Access frequently used administration features.
        </p>
    </div>

    <div class="card-body p-4 pt-0">
        <div class="row g-3">

            <div class="col-md-4">
                <a href="{{ route('admin.students.index') }}"
                   class="btn btn-outline-primary w-100 p-3 text-start">
                    <i class="bi bi-person-lines-fill fs-4 d-block mb-2"></i>
                    <span class="fw-semibold">Student Management</span>
                    <small class="d-block mt-1">
                        View and update student records
                    </small>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('admin.products.index') }}"
                   class="btn btn-outline-success w-100 p-3 text-start">
                    <i class="bi bi-box-seam fs-4 d-block mb-2"></i>
                    <span class="fw-semibold">Product Management</span>
                    <small class="d-block mt-1">
                        Manage your product catalogue
                    </small>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('admin.orders.index') }}"
                   class="btn btn-outline-warning w-100 p-3 text-start">
                    <i class="bi bi-receipt fs-4 d-block mb-2"></i>
                    <span class="fw-semibold">Order Management</span>
                    <small class="d-block mt-1">
                        Review and process customer orders
                    </small>
                </a>
            </div>

        </div>
    </div>

</div>

{{-- Dashboard Information --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-info-circle fs-3 text-primary"></i>

            <div>
                <h6 class="fw-bold mb-1">Administration Overview</h6>
                <p class="text-muted mb-0">
                    Use this dashboard to manage registered students,
                    maintain your product catalogue, and monitor orders
                    from a single place. The statistics above reflect
                    the current records stored in your database.
                </p>
            </div>
        </div>

    </div>
</div>

@endsection
