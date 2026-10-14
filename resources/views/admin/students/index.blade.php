@extends('layouts.admin')

@section('title', 'Students')

@section('content')

{{-- Page Header --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Students</h2>
        <p class="text-muted mb-0">
            Manage student records and academic information
        </p>
    </div>

    <span class="badge text-bg-primary fs-6 px-3 py-2">
        <i class="bi bi-people me-2"></i>
        {{ $students->count() }} student(s)
    </span>
</div>

{{-- Search Students --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">

        <form action="{{ route('admin.students.index') }}"
              method="GET">

            <div class="row g-2 align-items-center">

                <div class="col-md-9">
                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by name, email, phone, college..."
                            value="{{ $search }}"
                        >

                    </div>
                </div>

                <div class="col-md-3 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-grow-1">
                        Search
                    </button>

                    @if ($search !== '')
                        <a href="{{ route('admin.students.index') }}"
                           class="btn btn-outline-secondary">
                            Clear
                        </a>
                    @endif

                </div>

            </div>

        </form>

    </div>
</div>

{{-- Students Table --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 p-3">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-mortarboard text-primary me-2"></i>
            Student Directory
        </h5>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">
                    <tr>
                        <th class="px-3 py-3">S.No</th>
                        <th class="py-3">Student</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Phone</th>
                        <th class="py-3">College</th>
                        <th class="py-3">Qualification</th>
                        <th class="text-center py-3">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($students as $student)

                        <tr>
                            {{-- Serial Number --}}
                            <td class="px-3">
                                {{ $loop->iteration }}
                            </td>

                            {{-- Student Name --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">

                                    @if ($student->profile_image)
                                        <img
                                            src="{{ asset('storage/' . $student->profile_image) }}"
                                            alt="Profile"
                                            class="rounded-circle img-thumbnail"
                                            width="42"
                                            height="42"
                                        >
                                    @else
                                        <i class="bi bi-person-circle fs-3 text-secondary"></i>
                                    @endif

                                    <span class="fw-semibold">
                                        {{ $student->user->name }}
                                    </span>

                                </div>
                            </td>

                            {{-- Email --}}
                            <td>
                                <span class="text-break">
                                    {{ $student->user->email }}
                                </span>
                            </td>

                            {{-- Phone --}}
                            <td>
                                {{ $student->user->phone }}
                            </td>

                            {{-- College --}}
                            <td>
                                {{ $student->college ?? '—' }}
                            </td>

                            {{-- Qualification --}}
                            <td>
                                <span class="badge text-bg-light border">
                                    {{ $student->qualification ?? 'Not provided' }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="text-center">
                                <div class="d-inline-flex gap-1">

                                    {{-- View --}}
                                    <a
                                        href="{{ route('admin.students.show', $student) }}"
                                        class="btn btn-sm btn-outline-info"
                                        title="View Student"
                                        aria-label="View Student"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.students.edit', $student) }}"
                                        class="btn btn-sm btn-outline-warning"
                                        title="Edit Student"
                                        aria-label="Edit Student"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.students.destroy', $student) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this student?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Delete Student"
                                            aria-label="Delete Student"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-5">

                                <i class="bi bi-people fs-1 text-secondary"></i>

                                <h5 class="mt-3 fw-semibold">
                                    No Students Found
                                </h5>

                                <p class="text-muted mb-0">
                                    Student records will appear here when available.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="card-footer bg-white border-0 text-muted small p-3">
        Showing {{ $students->count() }} student(s)
    </div>

</div>

@endsection
