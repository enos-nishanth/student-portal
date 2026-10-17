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
                                    Email<span class="text-danger">*</span>
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
                                    Phone Number<span class="text-danger">*</span>
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
                                    Password<span class="text-danger">*</span>
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
                                    Confirm Password<span class="text-danger">*</span>
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
                                    Full Name<span class="text-danger">*</span>
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
                                    Date of Birth<span class="text-danger">*</span>
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
                                Gender<span class="text-danger">*</span>
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
                                    Address<span class="text-danger">*</span>
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
                                    City<span class="text-danger">*</span>
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
                                    Pincode<span class="text-danger">*</span>
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
                                    Highest Qualification<span class="text-danger">*</span>
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
                                    College / Institution<span class="text-danger">*</span>
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
                                    Graduation Year<span class="text-danger">*</span>
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
                                    Skills<span class="text-danger">*</span>
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
                                    Profile <span class="text-danger">*</span>
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
                                    Resume<span class="text-danger">*</span>
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
<script>
    const form = document.querySelector('form');

if (form) {

    // Get all fields
    const email = document.getElementById('email');
    const phone = document.getElementById('phone');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    const name = document.getElementById('name');
    const dob = document.getElementById('dob');

    const gender = document.querySelectorAll('input[name="gender"]');

    const address = document.getElementById('address');
    const city = document.getElementById('city');
    const pincode = document.getElementById('pincode');

    const qualification = document.getElementById('qualification');
    const college = document.getElementById('college');
    const graduationYear = document.getElementById('graduation_year');
    const skills = document.getElementById('skills');

    const profileImage = document.getElementById('profile_image');
    const resume = document.getElementById('resume');

    // Helper function
    function showError(input, message) {

        input.classList.add('is-invalid');

        let errorElement = input.parentElement.querySelector('.frontend-error');

        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.classList.add('invalid-feedback', 'frontend-error');
            input.parentElement.appendChild(errorElement);
        }

        errorElement.textContent = message;
    }


    function clearError(input) {

        input.classList.remove('is-invalid');

        const errorElement = input.parentElement.querySelector('.frontend-error');

        if (errorElement) {
            errorElement.remove();
        }
    }

    // Email validation
    function validateEmail() {

        const value = email.value.trim();

        clearError(email);

        if (value === '') {
            showError(email, 'Email is required.');
            return false;
        }

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(value)) {
            showError(email, 'Please enter a valid email address.');
            return false;
        }

        return true;
    }

    // Phone validation
    function validatePhone() {

        const value = phone.value.trim();

        clearError(phone);

        if (value === '') {
            showError(phone, 'Phone number is required.');
            return false;
        }

        if (!/^\d{10}$/.test(value)) {
            showError(phone, 'Phone number must contain exactly 10 digits.');
            return false;
        }

        return true;
    }

    // Password validation
    function validatePassword() {

        const value = password.value;

        clearError(password);

        if (value === '') {
            showError(password, 'Password is required.');
            return false;
        }

        if (value.length < 8) {
            showError(password, 'Password must be at least 8 characters.');
            return false;
        }

        return true;
    }

   // Confirm password validation
    function validatePasswordConfirmation() {

        const value = passwordConfirmation.value;

        clearError(passwordConfirmation);

        if (value === '') {
            showError(passwordConfirmation, 'Please confirm your password.');
            return false;
        }

        if (value !== password.value) {
            showError(passwordConfirmation, 'Passwords do not match.');
            return false;
        }

        return true;
    }

    // Full name validation
    function validateName() {

        const value = name.value.trim();

        clearError(name);

        if (value === '') {
            showError(name, 'Full name is required.');
            return false;
        }

        if (!/^[A-Za-z\s]+$/.test(value)) {
            showError(name, 'Name can contain only letters and spaces.');
            return false;
        }

        return true;
    }

    // Date of Birth validation
    function validateDob() {

        const value = dob.value;

        clearError(dob);

        if (value === '') {
            showError(dob, 'Date of birth is required.');
            return false;
        }

        const selectedDate = new Date(value);
        const today = new Date();

        // Remove time portion
        today.setHours(0, 0, 0, 0);

        if (selectedDate > today) {
            showError(dob, 'Date of birth cannot be in the future.');
            return false;
        }

        return true;
    }

    // Gender validation
    function validateGender() {

        const selectedGender = document.querySelector(
            'input[name="gender"]:checked'
        );

        const genderContainer = gender[0].parentElement.parentElement;

        let errorElement = genderContainer.querySelector('.frontend-error');

        if (errorElement) {
            errorElement.remove();
        }

        gender.forEach(input => {
            input.classList.remove('is-invalid');
        });

        if (!selectedGender) {

            errorElement = document.createElement('div');

            errorElement.classList.add(
                'invalid-feedback',
                'frontend-error',
                'd-block'
            );

            errorElement.textContent = 'Please select your gender.';

            genderContainer.appendChild(errorElement);

            return false;
        }

        return true;
    }

    // Address validation
    function validateAddress() {

        const value = address.value.trim();

        clearError(address);

        if (value === '') {
            showError(address, 'Address is required.');
            return false;
        }

        return true;
    }

    // City validation
    function validateCity() {

        const value = city.value.trim();

        clearError(city);

        if (value === '') {
            showError(city, 'City is required.');
            return false;
        }

        if (!/^[A-Za-z\s]+$/.test(value)) {
            showError(city, 'City can contain only letters and spaces.');
            return false;
        }

        return true;
    }

    // Pincode validation
    function validatePincode() {

        const value = pincode.value.trim();

        clearError(pincode);

        if (value === '') {
            showError(pincode, 'Pincode is required.');
            return false;
        }

        if (!/^\d{6}$/.test(value)) {
            showError(pincode, 'Pincode must contain exactly 6 digits.');
            return false;
        }

        return true;
    }


    // Qualification validation
    function validateQualification() {

        clearError(qualification);

        if (qualification.value === '') {
            showError(qualification, 'Please select your qualification.');
            return false;
        }

        return true;
    }



    // College validation
    function validateCollege() {

        const value = college.value.trim();

        clearError(college);

        if (value === '') {
            showError(college, 'College / Institution is required.');
            return false;
        }
      

        return true;
    }

    // Graduation year validation
    function validateGraduationYear() {

        clearError(graduationYear);

        if (graduationYear.value === '') {
            showError(graduationYear, 'Please select your graduation year.');
            return false;
        }

        return true;
    }

    // Skills validation
    function validateSkills() {

        const value = skills.value.trim();

        clearError(skills);

        // Skills is currently optional in your form.
        if (value === '') {
            return true;
        }

        if (value.length > 255) {
            showError(skills, 'Skills cannot exceed 255 characters.');
            return false;
        }

        return true;
    }

    // Profile image validation
    function validateProfileImage() {

        clearError(profileImage);

        if (profileImage.files.length === 0) {
            showError(profileImage, 'Profile image is required.');
            return false;
        }

        const file = profileImage.files[0];

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!allowedTypes.includes(file.type)) {
            showError(
                profileImage,
                'Profile image must be JPG, PNG or WebP.'
            );

            return false;
        }

        // 2 MB
        if (file.size > 2 * 1024 * 1024) {
            showError(
                profileImage,
                'Profile image must not exceed 2 MB.'
            );

            return false;
        }

        return true;
    }


    // Resume validation
    function validateResume() {

        clearError(resume);

        if (resume.files.length === 0) {
            showError(resume, 'Resume is required.');
            return false;
        }

        const file = resume.files[0];

        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        const allowedExtensions = [
            'pdf',
            'doc',
            'docx'
        ];

        const extension = file.name
            .split('.')
            .pop()
            .toLowerCase();

        if (
            !allowedTypes.includes(file.type) ||
            !allowedExtensions.includes(extension)
        ) {
            showError(
                resume,
                'Resume must be PDF, DOC or DOCX.'
            );

            return false;
        }

        // 5 MB
        if (file.size > 5 * 1024 * 1024) {
            showError(
                resume,
                'Resume must not exceed 5 MB.'
            );

            return false;
        }

        return true;
    }

    // Validate when form is submitted
    form.addEventListener('submit', function (event) {

        const isValid =
            validateEmail() &&
            validatePhone() &&
            validatePassword() &&
            validatePasswordConfirmation() &&
            validateName() &&
            validateDob() &&
            validateGender() &&
            validateAddress() &&
            validateCity() &&
            validatePincode() &&
            validateQualification() &&
            validateCollege() &&
            validateGraduationYear() &&
            validateSkills() &&
            validateProfileImage() &&
            validateResume();

        if (!isValid) {
            event.preventDefault();
        }
    });

    // Validate while user leaves the field
    email.addEventListener('blur', validateEmail);

    phone.addEventListener('blur', validatePhone);

    password.addEventListener('blur', validatePassword);

    passwordConfirmation.addEventListener(
        'blur',
        validatePasswordConfirmation
    );

    name.addEventListener('blur', validateName);

    dob.addEventListener('blur', validateDob);

    gender.forEach(input => {
        input.addEventListener('change', validateGender);
    });

    address.addEventListener('blur', validateAddress);

    city.addEventListener('blur', validateCity);

    pincode.addEventListener('blur', validatePincode);

    qualification.addEventListener(
        'change',
        validateQualification
    );

    college.addEventListener('blur', validateCollege);

    graduationYear.addEventListener(
        'change',
        validateGraduationYear
    );

    skills.addEventListener('blur', validateSkills);

    profileImage.addEventListener(
        'change',
        validateProfileImage
    );

    resume.addEventListener(
        'change',
        validateResume
    );
}
</script>
</body>
</html>
