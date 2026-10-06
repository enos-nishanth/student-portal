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