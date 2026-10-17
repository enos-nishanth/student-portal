<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Login</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container">

    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-md-6 col-lg-5">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-primary text-white text-center py-4">

                    <h2 class="mb-1">
                        Login to your account
                    </h2>

                </div>


                <div class="card-body p-4 p-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('login.store') }}"
                        id="loginForm"
                        novalidate
                    >
                        @csrf

                        <!-- Email or Phone -->
                        <div class="mb-3">
                            <label for="login" class="form-label">
                                Email or Phone Number
                            </label>

                             <input
                                type="text"
                                class="form-control"
                                id="login"
                                name="login"
                                placeholder="Enter email or 10-digit phone number"
                                value="{{ old('login') }}"
                                autocomplete="username"
                                required
                                aria-describedby="loginError"
                            >

                            <div class="invalid-feedback" id="loginError">
                                Enter a valid email address or 10-digit phone number.
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                minlength="8"
                                required
                                aria-describedby="passwordError"
                            >

                            <div class="invalid-feedback" id="passwordError">
                                Password must contain at least 8 characters.
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mb-4">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="remember"
                                name="remember"
                                value="1"
                            >

                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">

                        <span class="text-muted">
                            Don't have an account?
                        </span>

                        <a
                            href="{{ route('register') }}"
                            class="text-decoration-none fw-semibold"
                        >
                            Create Account
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
    const loginForm = document.getElementById('loginForm');
    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');

    // Validate Email or Phone Number
    function validateLogin() {
        const value = loginInput.value.trim();

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phonePattern = /^[6-9]\d{9}$/;

        loginInput.setCustomValidity('');

        if (value === '') {
            loginInput.setCustomValidity(
                'Email or phone number is required.'
            );
        } else if (
            !emailPattern.test(value) &&
            !phonePattern.test(value)
        ) {
            loginInput.setCustomValidity(
                'Enter a valid email address or 10-digit phone number.'
            );
        }

        const isValid = loginInput.validity.valid;

        loginInput.classList.toggle('is-invalid', !isValid);

        // Remove the green valid state / tick
        loginInput.classList.remove('is-valid');

        return isValid;
    }

    // Validate Password
    function validatePassword() {
        const value = passwordInput.value;

        passwordInput.setCustomValidity('');

        if (value.trim() === '') {
            passwordInput.setCustomValidity(
                'Password is required.'
            );
        } else if (value.length < 8) {
            passwordInput.setCustomValidity(
                'Password must contain at least 8 characters.'
            );
        }

        const isValid = passwordInput.validity.valid;

        passwordInput.classList.toggle('is-invalid', !isValid);

        // Remove the green valid state / tick
        passwordInput.classList.remove('is-valid');

        return isValid;
    }

    // Validate while typing
    loginInput.addEventListener('input', validateLogin);
    passwordInput.addEventListener('input', validatePassword);

    // Validate when clicking outside the input
    loginInput.addEventListener('blur', validateLogin);
    passwordInput.addEventListener('blur', validatePassword);

    // Validate before submitting the form
    loginForm.addEventListener('submit', function (event) {
        const isLoginValid = validateLogin();
        const isPasswordValid = validatePassword();

        if (!isLoginValid || !isPasswordValid) {
            event.preventDefault();

            if (!isLoginValid) {
                loginInput.focus();
            } else {
                passwordInput.focus();
            }
        }
    });
</script>
</body>
</html>
