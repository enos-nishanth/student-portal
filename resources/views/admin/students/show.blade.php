@extends('layouts.admin')

@section('title', 'Student Details')

@section('content')

{{-- Page Header --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Student Details</h2>
        <p class="text-muted mb-0">View student profile and academic information</p>
    </div>

    <a href="{{ route('admin.students.index') }}"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Students
    </a>
</div>

{{-- Student Profile --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center g-4">

            <div class="col-auto">
                @if ($student->profile_image)
                    <img
                        src="{{ asset('storage/' . $student->profile_image) }}"
                        alt="Profile Image"
                        class="rounded-circle img-thumbnail"
                        width="110"
                        height="110"
                    >
                @else
                    <div class="rounded-circle bg-light border text-secondary
                                d-flex align-items-center justify-content-center"
                         style="width: 110px; height: 110px;">
                        <i class="bi bi-person-fill fs-1"></i>
                    </div>
                @endif
            </div>

            <div class="col">
                <h3 class="fw-bold mb-2">{{ $student->user->name }}</h3>

                <p class="text-muted mb-2">
                    <i class="bi bi-envelope me-2"></i>
                    {{ $student->user->email }}
                </p>

                <span class="badge text-bg-primary">
                    Student ID: {{ $student->id }}
                </span>
            </div>

        </div>
    </div>
</div>

{{-- Personal Information --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white p-3">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-person-vcard text-primary me-2"></i>
            Personal Information
        </h5>
    </div>

    <div class="card-body p-4">
        <div class="row g-4">

            <div class="col-md-6 col-lg-4">
                <div class="text-muted small mb-1">Full Name</div>
                <div class="fw-semibold">{{ $student->user->name }}</div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="text-muted small mb-1">Email Address</div>
                <div class="fw-semibold text-break">{{ $student->user->email }}</div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="text-muted small mb-1">Phone Number</div>
                <div class="fw-semibold">{{ $student->user->phone }}</div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="text-muted small mb-1">Date of Birth</div>
                <div class="fw-semibold">
                    {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d M Y') : 'Not provided' }}
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="text-muted small mb-1">Gender</div>
                <div class="fw-semibold text-capitalize">
                    {{ $student->gender ?? 'Not provided' }}
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="text-muted small mb-1">City</div>
                <div class="fw-semibold">{{ $student->city ?? 'Not provided' }}</div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="text-muted small mb-1">Pincode</div>
                <div class="fw-semibold">{{ $student->pincode ?? 'Not provided' }}</div>
            </div>

            <div class="col-12">
                <div class="text-muted small mb-1">Address</div>
                <div class="fw-semibold">{{ $student->address ?? 'Not provided' }}</div>
            </div>

        </div>
    </div>
</div>

{{-- Education Information --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white p-3">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-mortarboard text-primary me-2"></i>
            Education Information
        </h5>
    </div>

    <div class="card-body p-4">
        <div class="row g-4">

            <div class="col-md-6 col-lg-4">
                <div class="text-muted small mb-1">Highest Qualification</div>
                <div class="fw-semibold">
                    {{ $student->qualification ?? 'Not provided' }}
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="text-muted small mb-1">College / Institution</div>
                <div class="fw-semibold">
                    {{ $student->college ?? 'Not provided' }}
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="text-muted small mb-1">Graduation Year</div>
                <div class="fw-semibold">
                    {{ $student->graduation_year ?? 'Not provided' }}
                </div>
            </div>

            <div class="col-12">
                <div class="text-muted small mb-2">Skills</div>

                @if ($student->skills)
                    @foreach (explode(',', $student->skills) as $skill)
                        @if (trim($skill) !== '')
                            <span class="badge rounded-pill text-bg-primary me-1 mb-1 p-2">
                                {{ trim($skill) }}
                            </span>
                        @endif
                    @endforeach
                @else
                    <span class="text-muted">No skills provided</span>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- Documents --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white p-3">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-folder2-open text-primary me-2"></i>
            Student Documents
        </h5>
    </div>

    <div class="card-body p-4">
        <div class="row g-3">

            {{-- Profile Image --}}
            <div class="col-md-6">
                <div class="border rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-person-bounding-box fs-2 text-primary"></i>

                        <div>
                            <h6 class="fw-bold mb-1">Profile Image</h6>
                            <p class="text-muted small mb-0">Student photograph</p>
                        </div>
                    </div>

                    @if ($student->profile_image)
                        <a href="{{ asset('storage/' . $student->profile_image) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn btn-outline-primary btn-sm mt-3">
                            <i class="bi bi-eye me-1"></i>View Image
                        </a>
                    @else
                        <p class="text-muted small mt-3 mb-0">Not uploaded</p>
                    @endif
                </div>
            </div>

            {{-- Resume --}}
            <div class="col-md-6">
                <div class="border rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-file-earmark-pdf fs-2 text-danger"></i>

                        <div>
                            <h6 class="fw-bold mb-1">Resume</h6>
                            <p class="text-muted small mb-0">Student resume document</p>
                        </div>
                    </div>

                    @if ($student->resume)
                        <a href="{{ asset('storage/' . $student->resume) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn btn-outline-primary btn-sm mt-3">
                            <i class="bi bi-box-arrow-up-right me-1"></i>View Resume
                        </a>
                    @else
                        <p class="text-muted small mt-3 mb-0">Not uploaded</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
