@extends('layouts.admin')

@section('title', 'Student Details')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Student Details</h2>

        <a
            href="{{ route('admin.students.index') }}"
            class="btn btn-secondary"
        >
            Back
        </a>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <h5 class="mb-4">
                Personal Information
            </h5>

            <div class="col">

                <div class="col-md-6 mb-3">
                    <strong>Name:</strong>
                    {{ $student->user->name }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Email:</strong>
                    {{ $student->user->email }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Phone:</strong>
                    {{ $student->user->phone }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Date of Birth:</strong>
                    {{ $student->dob }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Gender:</strong>
                    {{ $student->gender }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Qualification:</strong>
                    {{ $student->qualification }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>College:</strong>
                    {{ $student->college }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Graduation Year:</strong>
                    {{ $student->graduation_year }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>City:</strong>
                    {{ $student->city }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Pincode:</strong>
                    {{ $student->pincode }}
                </div>

                <div class="col-12 mb-3">
                    <strong>Address:</strong>
                    {{ $student->address }}
                </div>

                <div class="col-12 mb-3">
                    <strong>Skills:</strong>
                    {{ $student->skills ?? 'Not provided' }}
                </div>

                <div class="col-12 mb-3">

                  <strong>Profile:</strong>

                  <div class="mt-2">

                        @if ($student->profile_image)

                              <img
                                    src="{{ asset('storage/' . $student->profile_image) }}"
                                    alt="Profile Image"
                                    class="img-thumbnail"
                                    style="width: 150px; height: 150px; object-fit: cover;"
                              >

                        @else

                              <p class="text-muted mb-0">
                                    No profile image
                               </p>

                        @endif

                        </div>

                  </div>

            </div>

        </div>

    </div>

@endsection