
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS OTP Verification</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow-sm border-0 w-100" style="max-width: 420px;">
            <div class="card-body p-4 p-md-5">

                <div class="text-center mb-4">
                    <div class="fs-1 mb-2"><i class="bi bi-shield-lock-fill text-primary"></i></div>

                    <h3 class="fw-bold">Two-Factor Authentication</h3>

                    <p class="text-muted mb-0">
                        Enter the verification code sent to your registered mobile number.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.otp.verify') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="otp" class="form-label">
                            Verification Code <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="otp"
                            id="otp"
                            class="form-control form-control-lg text-center @error('otp') is-invalid @enderror"
                            placeholder="Enter OTP"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            pattern="[0-9]{4,6}"
                            minlength="4"
                            maxlength="6"
                            required
                            autofocus
                        >

                        @error('otp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        Verify & Continue
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        Cancel and return to login
                    </a>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
