<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Student Portal')
    </title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

</head>


<body class="bg-light">

<div class="d-flex min-vh-100">


    <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-primary"
         style="width: 250px;">

        {{-- Logo --}}
        <a
            href="#"
            class="d-flex align-items-center mb-4 text-white text-decoration-none"
        >
            

            <span class="fs-5 fw-bold">
                Student Portal
            </span>
        </a>


        <hr>


        {{-- Navigation --}}
        <ul class="nav nav-pills flex-column mb-auto">

            {{-- Dashboard --}}
            <li class="nav-item">

                <a
                    href="{{ route('dashboard') }}"
                    class="nav-link active"
                >
                    Dashboard
                </a>

            </li>


            {{-- Products --}}
            <li>

                <a
                    href="{{ route('student.products.index') }}"
                    class="nav-link text-white"
                >
                    Products
                </a>

            </li>

            <li class="nav-item">
                <a
                    href="{{ route('student.cart.index') }}"
                    class="nav-link  text-white"
                >
                    My Cart
                </a>
            </li>


            {{-- My Purchases --}}
            <li class="nav-item">

                <a 
                    href="{{ route('student.orders.index') }}"
                    class="nav-link  text-white"
                >
                    <span>My Purchases</span>
                </a>

            </li>

        </ul>


        <hr>


        {{-- User --}}
        <div class="mb-2">

            <div class="text-white mb-2">

                <i class="bi bi-person-circle me-2"></i>

                {{ auth()->user()->name ?? 'Student' }}

            </div>


            {{-- Logout --}}
            <form
                method="POST"
                action="#"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-outline-light btn-sm w-100"
                >
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Logout
                </button>

            </form>

        </div>

    </div>

    <main class="flex-grow-1">

        <div class="container-fluid p-4">

            @yield('content')

        </div>

    </main>


</div>


{{-- Bootstrap JS --}}
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>
@stack('scripts')
</body>

</html>