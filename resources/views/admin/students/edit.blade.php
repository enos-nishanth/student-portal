@extends('layouts.admin')

@section('title', 'Edit Student')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Edit Student</h2>

        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
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
                action="{{ route('admin.students.update', $student) }}"
                method="POST"
                id="editStudentForm"
                novalidate
            >
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $student->user->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                            minlength="2"
                            maxlength="100"
                            autocomplete="name"
                            required
                            aria-describedby="nameError"
                        >
                        <div class="invalid-feedback" id="nameError">
                            Enter a valid name using 2–100 characters.
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $student->user->email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            maxlength="255"
                            autocomplete="email"
                            required
                            aria-describedby="emailError"
                        >
                        <div class="invalid-feedback" id="emailError">
                            Enter a valid email address.
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $student->user->phone) }}"
                            class="form-control @error('phone') is-invalid @enderror"
                            inputmode="numeric"
                            maxlength="10"
                            autocomplete="tel-national"
                            required
                            aria-describedby="phoneError"
                        >
                        <div class="invalid-feedback" id="phoneError">
                            Enter a valid 10-digit Indian mobile number.
                        </div>
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Date of Birth --}}
                    <div class="col-md-6 mb-3">
                        <label for="dob" class="form-label">Date of Birth<span class="text-danger">*</span></label>
                        <input
                            type="date"
                            id="dob"
                            name="dob"
                            value="{{ old('dob', $student->dob ? \Illuminate\Support\Carbon::parse($student->dob)->format('Y-m-d') : '') }}"
                            class="form-control @error('dob') is-invalid @enderror"
                            required
                            aria-describedby="dobError"
                        >
                        <div class="invalid-feedback" id="dobError">
                            Date of birth must correspond to an age between 15 and 45.
                        </div>
                        @error('dob')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gender --}}
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Gender<span class="text-danger">*</span></label>
                        <select
                            id="gender"
                            name="gender"
                            class="form-select @error('gender') is-invalid @enderror"
                            required
                            aria-describedby="genderError"
                        >
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $student->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="invalid-feedback" id="genderError">
                            Please select a gender.
                        </div>
                        @error('gender')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="col-12 mb-3">
                        <label for="address" class="form-label">Address<span class="text-danger">*</span></label>
                        <textarea
                            id="address"
                            name="address"
                            class="form-control @error('address') is-invalid @enderror"
                            rows="3"
                            minlength="5"
                            maxlength="500"
                            required
                            aria-describedby="addressError"
                        >{{ old('address', $student->address) }}</textarea>
                        <div class="invalid-feedback" id="addressError">
                            Address must contain 5–500 characters.
                        </div>
                        @error('address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- City --}}
                    <div class="col-md-6 mb-3">
                        <label for="city" class="form-label">City<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            id="city"
                            name="city"
                            value="{{ old('city', $student->city) }}"
                            class="form-control @error('city') is-invalid @enderror"
                            minlength="2"
                            maxlength="100"
                            required
                            aria-describedby="cityError"
                        >
                        <div class="invalid-feedback" id="cityError">
                            Enter a valid city name.
                        </div>
                        @error('city')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pincode --}}
                    <div class="col-md-6 mb-3">
                        <label for="pincode" class="form-label">Pincode<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            id="pincode"
                            name="pincode"
                            value="{{ old('pincode', $student->pincode) }}"
                            class="form-control @error('pincode') is-invalid @enderror"
                            inputmode="numeric"
                            maxlength="6"
                            required
                            aria-describedby="pincodeError"
                        >
                        <div class="invalid-feedback" id="pincodeError">
                            Enter a valid 6-digit Indian pincode.
                        </div>
                        @error('pincode')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Qualification dropdown --}}
                    <div class="col-md-6 mb-3">
                        <label for="qualification" class="form-label">Highest Qualification<span class="text-danger">*</span></label>
                        <select
                            id="qualification"
                            name="qualification"
                            class="form-select @error('qualification') is-invalid @enderror"
                            required
                            aria-describedby="qualificationError"
                        >
                            <option value="">Select Qualification</option>
                            <option value="10th" {{ old('qualification', $student->qualification) === '10th' ? 'selected' : '' }}>10th</option>
                            <option value="12th" {{ old('qualification', $student->qualification) === '12th' ? 'selected' : '' }}>12th</option>
                            <option value="diploma" {{ old('qualification', $student->qualification) === 'diploma' ? 'selected' : '' }}>Diploma</option>
                            <option value="ug" {{ old('qualification', $student->qualification) === 'ug' ? 'selected' : '' }}>Undergraduate (UG)</option>
                            <option value="pg" {{ old('qualification', $student->qualification) === 'pg' ? 'selected' : '' }}>Postgraduate (PG)</option>
                            <option value="phd" {{ old('qualification', $student->qualification) === 'phd' ? 'selected' : '' }}>PhD</option>
                        </select>
                        <div class="invalid-feedback" id="qualificationError">
                            Please select a valid qualification.
                        </div>
                        @error('qualification')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- College --}}
                    <div class="col-md-6 mb-3">
                        <label for="college" class="form-label">College / Institution<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            id="college"
                            name="college"
                            value="{{ old('college', $student->college) }}"
                            class="form-control @error('college') is-invalid @enderror"
                            minlength="2"
                            maxlength="200"
                            required
                            aria-describedby="collegeError"
                        >
                        <div class="invalid-feedback" id="collegeError">
                            Enter a college or institution name between 2 and 200 characters.
                        </div>
                        @error('college')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Graduation Year --}}
                    <div class="col-md-6 mb-3">
                        <label for="graduation_year" class="form-label">Graduation Year<span class="text-danger">*</span></label>
                        <input
                            type="number"
                            id="graduation_year"
                            name="graduation_year"
                            value="{{ old('graduation_year', $student->graduation_year) }}"
                            class="form-control @error('graduation_year') is-invalid @enderror"
                            min="1900"
                            max="{{ now()->year }}"
                            step="1"
                            required
                            aria-describedby="graduationYearError"
                        >
                        <div class="invalid-feedback" id="graduationYearError">
                            Enter a valid graduation year that is not in the future.
                        </div>
                        @error('graduation_year')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Skills --}}
                    <div class="col-12 mb-3">
                        <label for="skills" class="form-label">Skills<span class="text-danger">*</span></label>
                        <textarea
                            id="skills"
                            name="skills"
                            class="form-control @error('skills') is-invalid @enderror"
                            rows="3"
                            maxlength="1000"
                            aria-describedby="skillsError"
                        >{{ old('skills', $student->skills) }}</textarea>
                        <div class="form-text">Optional. Maximum 1000 characters.</div>
                        <div class="invalid-feedback" id="skillsError">
                            Skills cannot exceed 1000 characters.
                        </div>
                        @error('skills')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Update Student
                    </button>

                    <a href="{{ route('admin.students.index') }}" class="btn btn-light">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editStudentForm');

    if (!form) return;

    const currentYear = new Date().getFullYear();

    const fields = {
        name: document.getElementById('name'),
        email: document.getElementById('email'),
        phone: document.getElementById('phone'),
        dob: document.getElementById('dob'),
        gender: document.getElementById('gender'),
        address: document.getElementById('address'),
        city: document.getElementById('city'),
        pincode: document.getElementById('pincode'),
        qualification: document.getElementById('qualification'),
        college: document.getElementById('college'),
        graduation_year: document.getElementById('graduation_year'),
        skills: document.getElementById('skills')
    };

    const qualificationValues = ['10th', '12th', 'diploma', 'ug', 'pg', 'phd'];
    const genderValues = ['male', 'female', 'other'];

    function feedbackFor(field) {
        return document.getElementById(field.id + 'Error')
            || field.parentElement.querySelector('.invalid-feedback');
    }

    function setError(field, message) {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');

        const feedback = feedbackFor(field);
        if (feedback) {
            feedback.textContent = message;
            feedback.style.display = 'block';
        }

        field.setAttribute('aria-invalid', 'true');
        return false;
    }

    function setValid(field) {
        // Deliberately do not add Bootstrap's is-valid class:
        // this avoids the green valid-state tick.
        field.classList.remove('is-invalid', 'is-valid');

        const feedback = feedbackFor(field);
        if (feedback) feedback.style.display = 'none';

        field.setAttribute('aria-invalid', 'false');
        return true;
    }

    function validateName() {
        const value = fields.name.value.trim();
        if (!/^[\p{L}\p{M}\s.'-]{2,100}$/u.test(value)) {
            return setError(fields.name, 'Enter a valid name using 2–100 characters.');
        }
        return setValid(fields.name);
    }

    function validateEmail() {
        const value = fields.email.value.trim();
        if (value.length > 255 || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            return setError(fields.email, 'Enter a valid email address.');
        }
        return setValid(fields.email);
    }

    function validatePhone() {
        const value = fields.phone.value.trim();
        if (!/^[6-9]\d{9}$/.test(value)) {
            return setError(fields.phone, 'Enter a valid 10-digit Indian mobile number.');
        }
        return setValid(fields.phone);
    }

    function validateDob() {
        const value = fields.dob.value;
        if (!value) {
            return setError(fields.dob, 'Date of birth is required.');
        }

        const dob = new Date(value + 'T00:00:00');
        const today = new Date();

        if (Number.isNaN(dob.getTime()) || dob >= new Date(today.getFullYear(), today.getMonth(), today.getDate())) {
            return setError(fields.dob, 'Date of birth must be in the past.');
        }

        let age = today.getFullYear() - dob.getFullYear();
        const birthdayPending =
            today.getMonth() < dob.getMonth() ||
            (today.getMonth() === dob.getMonth() && today.getDate() < dob.getDate());

        if (birthdayPending) age--;

        if (age < 15 || age > 45) {
            return setError(fields.dob, 'Student age must be between 15 and 45.');
        }

        return setValid(fields.dob);
    }

    function validateGender() {
        if (!genderValues.includes(fields.gender.value)) {
            return setError(fields.gender, 'Please select a valid gender.');
        }
        return setValid(fields.gender);
    }

    function validateAddress() {
        const value = fields.address.value.trim();
        if (value.length < 5 || value.length > 500) {
            return setError(fields.address, 'Address must contain 5–500 characters.');
        }
        return setValid(fields.address);
    }

    function validateCity() {
        const value = fields.city.value.trim();
        if (value.length < 2 || value.length > 100 || !/^[\p{L}\p{M}\s.'-]+$/u.test(value)) {
            return setError(fields.city, 'Enter a valid city name.');
        }
        return setValid(fields.city);
    }

    function validatePincode() {
        if (!/^[1-9]\d{5}$/.test(fields.pincode.value.trim())) {
            return setError(fields.pincode, 'Enter a valid 6-digit Indian pincode.');
        }
        return setValid(fields.pincode);
    }

    function validateQualification() {
        if (!qualificationValues.includes(fields.qualification.value)) {
            return setError(fields.qualification, 'Please select a valid qualification.');
        }
        return setValid(fields.qualification);
    }

    function validateCollege() {
        const value = fields.college.value.trim();
        if (value.length < 2 || value.length > 200) {
            return setError(fields.college, 'College name must contain 2–200 characters.');
        }
        return setValid(fields.college);
    }

    function validateGraduationYear() {
        const value = fields.graduation_year.value.trim();
        const year = Number(value);

        if (!/^\d{4}$/.test(value) || year < 1900 || year > currentYear) {
            return setError(fields.graduation_year, 'Enter a valid graduation year that is not in the future.');
        }
        return setValid(fields.graduation_year);
    }

    function validateSkills() {
        if (fields.skills.value.length > 1000) {
            return setError(fields.skills, 'Skills cannot exceed 1000 characters.');
        }
        return setValid(fields.skills);
    }

    const validators = [
        [fields.name, validateName],
        [fields.email, validateEmail],
        [fields.phone, validatePhone],
        [fields.dob, validateDob],
        [fields.gender, validateGender],
        [fields.address, validateAddress],
        [fields.city, validateCity],
        [fields.pincode, validatePincode],
        [fields.qualification, validateQualification],
        [fields.college, validateCollege],
        [fields.graduation_year, validateGraduationYear],
        [fields.skills, validateSkills]
    ];

    validators.forEach(function ([field, validator]) {
        field.addEventListener('input', validator);
        field.addEventListener('change', validator);
        field.addEventListener('blur', validator);
    });

    form.addEventListener('submit', function (event) {
        let firstInvalidField = null;

        validators.forEach(function ([field, validator]) {
            if (!validator() && !firstInvalidField) {
                firstInvalidField = field;
            }
        });

        if (firstInvalidField) {
            event.preventDefault();
            firstInvalidField.focus();
        }
    });
});
</script>
@endpush
