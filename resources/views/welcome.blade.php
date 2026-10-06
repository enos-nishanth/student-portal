<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Portal</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

    {{-- Navbar --}}
    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container">

            <a href="{{ url('/') }}"
               class="navbar-brand fw-bold">
                Student Portal
            </a>

            <div class="d-flex gap-2">

                <a href="{{ route('login') }}"
                   class="btn btn-outline-primary">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="btn btn-primary">
                    Register
                </a>

            </div>

        </div>
    </nav>


    {{-- Landing Content --}}
    <div class="container">

        <div class="row justify-content-center text-center">

            <div class="col-md-8">

                <div class="py-5 mt-5">

                    <h1 class="fw-bold mb-3">
                        Welcome to Student Portal
                    </h1>

                    <p class="text-muted fs-5 mb-4">
                        Manage your learning, profile and courses
                        in one place.
                    </p>

                    <div class="d-flex justify-content-center gap-3">

                        <a href="{{ route('login') }}"
                           class="btn btn-primary px-4">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                           class="btn btn-outline-primary px-4">
                            Register
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
