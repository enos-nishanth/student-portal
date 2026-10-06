<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registration</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    @vite(['resources/js/app.js'])      
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-10">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-primary text-white text-center py-4">
                    <h2 class="mb-1">Student Registration</h2>
                    <p class="mb-0">
                        Create your student account
                    </p>
                </div>


                <div class="card-body p-4 p-md-5">

                    <form
                        method="POST"
                        action="{{ route('register.store') }}"
                        enctype="multipart/form-data"
                    >

                        @csrf

                        <h5 class="border-bottom pb-2 mb-4">
                            Account Information
                        </h5>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label
                                    for="email"
                                    class="form-label"
                                >
                                    Email
                                </label>

                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    placeholder="Enter your email"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="phone"
                                    class="form-label"
                                >
                                    Phone Number
                                </label>

                                <input
                                    type="tel"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    id="phone"
                                    name="phone"
                                    placeholder="Enter your phone number"
                                    required
                                >
                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="password"
                                    class="form-label"
                                >
                                    Password
                                </label>

                                <input
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    placeholder="Create a password"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label
                                    for="password_confirmation"
                                    class="form-label"
                                >
                                    Confirm Password
                                </label>

                                <input
                                    type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Confirm your password"
                                    required
                                >
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        <h5 class="border-bottom pb-2 mb-4 mt-5">
                            Personal Information
                        </h5>

                        <div class="row g-3">

                            <div class="col-12">

                                <label
                                    for="name"
                                    class="form-label"
                                >
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    placeholder="Enter your full name"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label
                                    for="dob"
                                    class="form-label"
                                >
                                    Date of Birth
                                </label>

                                <input
                                    type="date"
                                    class="form-control @error('dob') is-invalid @enderror"
                                    id="dob"
                                    name="dob"
                                    required
                                >
                                @error('dob')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        <div class="col-md-6">

                            <label class="form-label d-block">
                                Gender
                            </label>

                            <div class="form-check form-check-inline">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="gender"
                                    id="male"
                                    value="male"
                                    required
                                >

                                <label
                                    class="form-check-label"
                                    for="male"
                                >
                                    Male
                                </label>

                            </div>


                            <div class="form-check form-check-inline">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="gender"
                                    id="female"
                                    value="female"
                                >

                                <label
                                    class="form-check-label"
                                    for="female"
                                >
                                    Female
                                </label>

                            </div>


                            <div class="form-check form-check-inline">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="gender"
                                    id="other"
                                    value="other"
                                >

                                <label
                                    class="form-check-label"
                                    for="other"
                                >
                                    Other
                                </label>

                            </div>

                        </div>

                        </div>

                        <h5 class="border-bottom pb-2 mb-4 mt-5">
                            Address Information
                        </h5>

                        <div class="row g-3">

                            <div class="col-12">

                                <label
                                    for="address"
                                    class="form-label"
                                >
                                    Address
                                </label>

                                <textarea
                                    class="form-control @error('address') is-invalid @enderror"
                                    id="address"
                                    name="address"
                                    rows="3"
                                    placeholder="Enter your complete address"
                                    required
                                ></textarea>
                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-4">

                                <label
                                    for="city"
                                    class="form-label"
                                >
                                    City
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('city') is-invalid @enderror"
                                    id="city"
                                    name="city"
                                    placeholder="Enter city"
                                    required
                                >
                                @error('city')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-4">

                                <label
                                    for="pincode"
                                    class="form-label"
                                >
                                    Pincode
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('pincode') is-invalid @enderror"
                                    id="pincode"
                                    name="pincode"
                                    maxlength="6"
                                    placeholder="Enter pincode"
                                    required
                                >
                                @error('pincode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        <h5 class="border-bottom pb-2 mb-4 mt-5">
                            Education Information
                        </h5>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label
                                    for="qualification"
                                    class="form-label"
                                >
                                    Highest Qualification
                                </label>

                                <select
                                    class="form-select @error('qualification') is-invalid @enderror"
                                    id="qualification"
                                    name="qualification"
                                    required
                                >
                                @error('qualification')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                    <option value="">
                                        Select Qualification
                                    </option>

                                    <option value="10th">
                                        10th
                                    </option>

                                    <option value="12th">
                                        12th
                                    </option>

                                    <option value="diploma">
                                        Diploma
                                    </option>

                                    <option value="ug">
                                        Undergraduate
                                    </option>

                                    <option value="pg">
                                        Postgraduate
                                    </option>

                                    <option value="phd">
                                        PhD
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label
                                    for="college"
                                    class="form-label"
                                >
                                    College / Institution
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('college') is-invalid @enderror"
                                    id="college"
                                    name="college"
                                    placeholder="Enter your college"
                                    required
                                >
                                @error('college')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label
                                    for="graduation_year"
                                    class="form-label"
                                >
                                    Graduation Year
                                </label>

                                <select
                                    class="form-select @error('graduation_year') is-invalid @enderror"
                                    id="graduation_year"
                                    name="graduation_year"
                                    required
                                >
                                @error('graduation_year')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                    <option value="">
                                        Select Year
                                    </option>

                                    @for ($year = date('Y')-6; $year <= date('Y') + 6; $year++)

                                        <option value="{{ $year }}">
                                            {{ $year }}
                                        </option>

                                    @endfor

                                </select>

                            </div>

                            <div class="col-12">

                                <label
                                    for="skills"
                                    class="form-label"
                                >
                                    Skills
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('skills') is-invalid @enderror"
                                    id="skills"
                                    name="skills"
                                    placeholder="Example: Laravel, PHP, MySQL, Angular"
                                >
                                @error('skills')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Separate multiple skills with commas.
                                </div>

                            </div>

                        </div>

                        <h5 class="border-bottom pb-2 mb-4 mt-5">
                            Documents
                        </h5>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label
                                    for="profile_image"
                                    class="form-label"
                                >
       
                                </label>

                                <input
                                    type="file"
                                    class="form-control @error('profile_image') is-invalid @enderror"
                                    id="profile_image"
                                    name="profile_image"
                                    accept="image/jpeg,image/png,image/webp"
                                    required
                                >
                                @error('profile_image')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    JPG, PNG or WebP.
                                </div>

                            </div>

                            <div class="col-md-6">

                                <label
                                    for="resume"
                                    class="form-label"
                                >
                                    Resume
                                </label>

                                <input
                                    type="file"
                                    class="form-control @error('resume') is-invalid @enderror"
                                    id="resume"
                                    name="resume"
                                    accept=".pdf,.doc,.docx"
                                    required
                                >
                                @error('resume')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    PDF, DOC or DOCX.
                                </div>

                            </div>

                        </div>

                        <div class="d-grid mt-5">

                            <button
                                type="submit"
                                class="btn btn-primary btn-lg"
                            >
                                Create Account
                            </button>

                        </div>

                    </form>

                    <div class="text-center mt-4">

                        <span class="text-muted">
                            Already have an account?
                        </span>

                        <a
                            href="{{ route('login') }}"
                            class="text-decoration-none fw-semibold"
                        >
                            Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
