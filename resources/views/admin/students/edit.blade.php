@extends('layouts.admin')

@section('title', 'Edit Student')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Edit Student</h2>

        <a
            href="{{ route('admin.students.index') }}"
            class="btn btn-secondary"
        >
            Back
        </a>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <form
                action="{{ route('admin.students.update', $student) }}"
                method="POST"
            >

                @csrf

                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $student->user->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
                    >

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $student->user->email) }}"
                        class="form-control @error('email') is-invalid @enderror"
                    >

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $student->user->phone) }}"
                        class="form-control @error('phone') is-invalid @enderror"
                    >

                    @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Date of Birth
                    </label>

                    <input
                        type="date"
                        name="dob"
                        value="{{ old('dob', $student->dob) }}"
                        class="form-control @error('dob') is-invalid @enderror"
                    >

                    @error('dob')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <!-- Gender -->
                <div class="mb-3">

                    <label class="form-label">
                        Gender
                    </label>

                    <select
                        name="gender"
                        class="form-select @error('gender') is-invalid @enderror"
                    >

                        <option value="">Select Gender</option>

                        <option
                            value="male"
                            {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}
                        >
                            Male
                        </option>

                        <option
                            value="female"
                            {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}
                        >
                            Female
                        </option>

                    </select>

                    @error('gender')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Address
                    </label>

                    <textarea
                        name="address"
                        class="form-control @error('address') is-invalid @enderror"
                        rows="3"
                    >{{ old('address', $student->address) }}</textarea>

                    @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        value="{{ old('city', $student->city) }}"
                        class="form-control @error('city') is-invalid @enderror"
                    >

                    @error('city')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Pincode
                    </label>

                    <input
                        type="text"
                        name="pincode"
                        value="{{ old('pincode', $student->pincode) }}"
                        class="form-control @error('pincode') is-invalid @enderror"
                    >

                    @error('pincode')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Qualification
                    </label>

                    <input
                        type="text"
                        name="qualification"
                        value="{{ old('qualification', $student->qualification) }}"
                        class="form-control @error('qualification') is-invalid @enderror"
                    >

                    @error('qualification')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        College
                    </label>

                    <input
                        type="text"
                        name="college"
                        value="{{ old('college', $student->college) }}"
                        class="form-control @error('college') is-invalid @enderror"
                    >

                    @error('college')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Graduation Year
                    </label>

                    <input
                        type="number"
                        name="graduation_year"
                        value="{{ old('graduation_year', $student->graduation_year) }}"
                        class="form-control @error('graduation_year') is-invalid @enderror"
                    >

                    @error('graduation_year')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Skills
                    </label>

                    <textarea
                        name="skills"
                        class="form-control @error('skills') is-invalid @enderror"
                        rows="3"
                    >{{ old('skills', $student->skills) }}</textarea>

                    @error('skills')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Student
                </button>

            </form>

        </div>

    </div>

@endsection
