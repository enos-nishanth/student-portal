<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Dashboard')</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

</head>

<body>

    <nav class="navbar navbar-dark bg-dark">

        <div class="container-fluid">

            <a class="navbar-brand" href="#">
                Admin Panel
            </a>

            <span class="text-white">
                {{ auth()->user()->name ?? 'Admin' }}
            </span>

        </div>

    </nav>


    <div class="container-fluid">

        <div class="row">

            <aside class="col-md-3 col-lg-2 bg-light min-vh-100 p-3">

                <h5 class="mb-4">
                    Menu
                </h5>

                <div class="nav flex-column">

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="nav-link"
                    >
                        Dashboard
                    </a>

                    <a
                        href="{{ route('admin.students.index') }}"
                        class="nav-link"
                    >
                        Students
                    </a>
                    <a
                        href="{{ route('admin.products.index') }}"
                        class="nav-link"
                    >
                        Products
                    </a>
                    
                    
                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="nav-link"
                    >
    Orders
</a>


                </div>

            </aside>



            <main class="col-md-9 col-lg-10 p-4">

                @yield('content')

            </main>

        </div>

    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>