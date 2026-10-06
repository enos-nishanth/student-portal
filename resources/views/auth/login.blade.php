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
                    >

                        @csrf

                        <div class="mb-3">

                            <label
                                for="login"
                                class="form-label"
                            >
                                Email or Phone Number
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="login"
                                name="login"
                                placeholder="Enter email or phone number"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label
                                for="password"
                                class="form-label"
                            >
                                Password
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                required
                            >

                        </div>

                        <div class="form-check mb-4">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="remember"
                                name="remember"
                            >

                            <label
                                class="form-check-label"
                                for="remember"
                            >
                                Remember me
                            </label>

                        </div>

                        <div class="d-grid">

                            <button
                                type="submit"
                                class="btn btn-primary btn-lg"
                            >
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

</body>
</html>
