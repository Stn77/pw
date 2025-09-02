<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title ?? config('app.name')}}</title>
    {{--
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    {{--
    <link href="{{ asset('css/stylingapp.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    @stack('css')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        <aside class="app-sidebar bg-body-secondary">
            <div class="d-flex flex-column flex-shrink-0 p-3 bg-white"
                style="width: 280px; height: 100vh; overflow-y: auto;">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                    {{-- <div class="sidebar-brand text-center py-3">
                        <a href="/" class="brand-link"> --}}
                            <img src="{{ asset('img/Prima Pay.png') }}" alt="Logo" class="brand-image opacity-75"
                                style="width: 150px;">
                            {{-- </a>
                    </div> --}}

                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <button class="btn btn-toggle align-items-center rounded collapsed w-100 text-start"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            Home
                        </button>
                        <div class="collapse show" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-3">
                                <li><a href="#" class="link-dark rounded d-block py-1">Overview</a></li>
                                <li><a href="#" class="link-dark rounded d-block py-1">Updates</a></li>
                                <li><a href="#" class="link-dark rounded d-block py-1">Reports</a></li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <button class="btn btn-toggle align-items-center rounded collapsed w-100 text-start"
                            data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                            Dashboard
                        </button>
                        <div class="collapse" id="dashboard-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-3">
                                <li><a href="#" class="link-dark rounded d-block py-1">Overview</a></li>
                                <li><a href="#" class="link-dark rounded d-block py-1">Weekly</a></li>
                                <li><a href="#" class="link-dark rounded d-block py-1">Monthly</a></li>
                                <li><a href="#" class="link-dark rounded d-block py-1">Annually</a></li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <button class="btn btn-toggle align-items-center rounded collapsed w-100 text-start"
                            data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                            Orders
                        </button>
                        <div class="collapse" id="orders-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-3">
                                <li><a href="#" class="link-dark rounded d-block py-1">New</a></li>
                                <li><a href="#" class="link-dark rounded d-block py-1">Processed</a></li>
                                <li><a href="#" class="link-dark rounded d-block py-1">Shipped</a></li>
                                <li><a href="#" class="link-dark rounded d-block py-1">Returned</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="border-top my-3"></li>

                    <li>
                        <button class="btn btn-toggle align-items-center rounded collapsed w-100 text-start"
                            data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                            Account
                        </button>
                        <div class="collapse" id="account-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-3">
                                <li><a href="#" class="link-dark rounded d-block py-1">New...</a></li>
                                <li><a href="#" class="link-dark rounded d-block py-1">Profile</a></li>
                                <li><a href="#" class="link-dark rounded d-block py-1">Settings</a></li>
                                <li><a href="#" class="link-dark rounded d-block py-1">Sign out</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>

        <div class="app-main">
            {{$slot}}
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
    <script src="{{asset('js/sidebars.js')}}"></script>
    {{-- <script src="{{ asset('js/adminlte.js') }}"></script> --}}
    @stack('script')
    <script>
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const logoutConfirmed = confirm('Apakah Anda yakin ingin logout?');
            if (logoutConfirmed) {
                window.location.href = "{{ route('logout') }}";
            }
        });
    </script>
</body>

</html>
