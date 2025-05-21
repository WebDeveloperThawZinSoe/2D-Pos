<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-2">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold text-primary" href="/">
                <img src="{{asset('logo.png')}}" style="width:50px;height:50px" alt=""> &nbsp; {{ env('APP_NAME') }}
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-mdb-toggle="offcanvas"
                data-mdb-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars fa-lg text-primary"></i>
            </button>

            <!-- Desktop Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto d-none d-lg-flex gap-3">

                </ul>

                <!-- Right Side (Auth Buttons) -->
                <ul class="navbar-nav d-none d-lg-flex">
                    @guest
                    <li class="nav-item">
                        <a class="btn btn-outline-primary me-2 px-3 rounded-pill" href="{{ route('login') }}">Login</a>
                    </li>

                    @else
                    @auth

                    @if(Auth::user()->hasRole('user'))
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-semibold" href="{{ route('routeCheck') }}">ပင်မစာမျက်နှာ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-semibold"
                            href="{{ route('select.date.section') }}">ရက်ရွှေးမည်</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-semibold" href="{{ route('user.my.order') }}">အရင်အော်ဒါများ</a>
                    </li>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle  text-dark  fw-semibold" href="#" id="navbarDropdown2"
                            role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                            စာရင်းချုပ်
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">

                            <li><a class="dropdown-item" href="/user/report/daily">နေ့စဉ်</a></li>
                            <li><a class="dropdown-item" href="/user/report/weekly">အပတ်စဉ်</a></li>
                            <li><a class="dropdown-item" href="/user/report/monthly">လစဉ်</a></li>
                            <li><a class="dropdown-item" href="/user/report/yearly">တနစ်စာ</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle  text-dark  fw-semibold" href="#" id="navbarDropdown"
                            role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                            အကောင့်
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- <li><a class="dropdown-item" href="{{ route('routeCheck') }}">Dashboard</a></li> -->
                            <li><a class="dropdown-item"
                                    href="{{ route('user.changePassword') }}">စကားဝှက်ပြောင်းမည်</a></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    ထွက်မည်
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>

                    @elseif(Auth::user()->hasRole('shop'))
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-semibold" href="{{ route('routeCheck') }}">ပင်မစာမျက်နှာ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-semibold"
                            href="{{ route('select.date.section') }}">ရက်ရွှေးမည်</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle  text-dark  fw-semibold" href="#" id="navbarDropdown2"
                            role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                            စာရင်းချုပ်
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">

                            <li><a class="dropdown-item" href="/dine/report/daily">နေ့စဉ်</a></li>
                            <li><a class="dropdown-item" href="/dine/report/weekly">အပတ်စဉ်</a></li>
                            <li><a class="dropdown-item" href="/dine/report/monthly">လစဉ်</a></li>
                            <li><a class="dropdown-item" href="/dine/report/yearly">တနစ်စာ</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle  text-dark  fw-semibold" href="#" id="navbarDropdown"
                            role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                            အကောင့်
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- <li><a class="dropdown-item" href="{{ route('routeCheck') }}">Dashboard</a></li> -->
                            <li><a class="dropdown-item"
                                    href="{{ route('user.changePassword') }}">စကားဝှက်ပြောင်းမည်</a></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    ထွက်မည်
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @endauth
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Offcanvas Sidebar (Mobile Menu) -->
    <div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold text-primary" id="sidebarMenuLabel"> <img src="{{asset('logo.png')}}"
                    style="width:50px;height:50px" alt=""> &nbsp; {{ env('APP_NAME') }}</h5>
            <button type="button" class="btn-close text-reset" data-mdb-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">


                @guest
                <li class="nav-item mt-3">
                    <a class="btn btn-outline-primary w-100 mb-2" href="{{ route('login') }}">Login</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="btn btn-primary w-100 text-white" href="{{ route('register') }}">Register Shop</a>
                </li> -->
                @endguest
                @auth


                @if(Auth::user()->hasRole('user'))
                <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold" href="{{ route('routeCheck') }}">ပင်မစာမျက်နှာ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold" href="{{ route('user.my.order') }}">အရင်အော်ဒါများ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold"
                        href="{{ route('user.changePassword') }}">စကားဝှက်ပြောင်းမည်</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger fw-semibold" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        Logout
                    </a>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

                @elseif(Auth::user()->hasRole('shop'))
                <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold" href="{{ route('dine.index') }}">ပင်မစာမျက်နှာ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold" href="{{ route('select.date.section') }}">ရက်ရွှေးမည်</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold" href="/dine/report/daily">စာရင်းချုပ် ( နေ့စဉ် ) </a>
                </li>

                 <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold" href="/dine/report/weekly">စာရင်းချုပ် ( အပတ်စဉ် )</a>
                </li>

                 <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold" href="/dine/report/monthly">စာရင်းချုပ် ( လစဉ် )</a>
                </li>

                 <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold" href="/dine/report/yearly">စာရင်းချုပ် ( တနစ်စာ )</a>
                </li>

                 <li class="nav-item">
                    <a class="nav-link text-dark fw-semibold"
                        href="{{ route('user.changePassword') }}">စကားဝှက်ပြောင်းမည်</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger fw-semibold" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        Logout
                    </a>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>


                
                @endif
                @endauth




            </ul>
        </div>
    </div>


    <div class="container mt-4">
        @yield('body')
    </div>



    <footer class="bg-dark text-white text-center text-lg-start mt-5">

        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2025 {{ env('APP_NAME') }}. All rights reserved.
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    @if (session('success'))
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        confirmButtonColor: '#3085d6',
        timer: 3000,
        timerProgressBar: true,
    });
    </script>
    @endif

    @if (session('error'))
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}",
        confirmButtonColor: '#d33',
        timer: 3000,
        timerProgressBar: true,
    });
    </script>
    @endif

</body>

</html>