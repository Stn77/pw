<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title ?? config('app.name')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    {{-- <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-eval' 'unsafe-inline' data: blob:;"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        /* Styling untuk sidebar responsif */
        .app-wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        .app-sidebar {
            min-height: 100vh;
            width: 280px;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.05), 0 2px 10px 0 rgba(0, 0, 0, 0.05);
        }

        .app-main {
            width: 100%;
            overflow-x: hidden;
            transition: all 0.3s;
        }

        /* Toggle button styling */
        .sidebar-toggle {
            display: none;
            top: 20px;
            left: 20px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 2px 10px;
            margin-right: 20px;
        }

        /* Overlay untuk mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Responsif untuk perangkat mobile */
        @media (max-width: 992px) {
            .app-sidebar {
                position: fixed;
                left: -280px;
                height: 100vh;
                overflow-y: auto;
            }

            .app-sidebar.active {
                left: 0;
            }

            .app-main {
                width: 100%;
            }

            .sidebar-toggle {
                display: block;
            }

            body.sidebar-open {
                overflow: hidden;
            }
        }
        .btn-toggle[aria-expanded="true"] {
            color: rgba(0, 0, 0, 0.85);
            z-index: 10;
        }

        .btn-toggle[aria-expanded="true"]::after {
            transform: rotate(90deg);
        }

        /* Sidebar styling */
        .app-sidebar {
            min-height: 100vh;
            width: 280px;
            transition: all 0.3s;
            z-index: 1000;
            background-color: #ffffff; /* sidebar putih biar clean */
            border-right: 1px solid #e2e8f0; /* garis halus */
        }

        .sidebar-brand {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .sidebar-content {
            height: calc(100vh - 80px);
            overflow-y: auto;
            padding: 0 0 1rem 0;
        }

        /* Menu utama */
        .crazy-nav {
            font-weight: 500;
            color: #1E293B; /* teks abu gelap */
            transition: background-color 0.2s, color 0.2s;
        }

        .crazy-nav:hover {
            background-color: #f1f5f9; /* hover abu muda */
            color: #2563EB; /* teks biru saat hover */
        }

        .bg-active {
            background-color: #2563EB !important; /* biru aktif */
            color: #ffffff !important;
        }

        .bg-active:hover {
            background-color: #1D4ED8 !important; /* biru lebih gelap */
            color: #ffffff !important;
        }

        /* Submenu */
        .btn-toggle {
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: #475569; /* abu medium */
            background-color: transparent;
            border: 0;
            width: 100%;
            text-align: left;
        }

        .btn-toggle:hover,
        .btn-toggle:focus {
            color: #2563EB;
            background-color: #f8fafc;
        }

        .btn-toggle[aria-expanded="true"] {
            color: #2563EB;
            background-color: #f1f5f9;
        }

        .btn-toggle-nav a, .btn-toggle-nav .logout {
            padding: 0.5rem 1.5rem;
            margin-top: 0.125rem;
            margin-left: 1.25rem;
            font-size: 0.875rem;
            color: #475569;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.2s, color 0.2s;
        }

        .btn-toggle-nav a:hover {
            background-color: #f1f5f9;
            color: #2563EB;
        }

        .sub-nav-active {
            background-color: #10B981 !important; /* hijau aktif */
            color: #ffffff !important;
            border-radius: 5px;
        }
        .logout button:hover {
            background-color: #fee2e2; /* merah muda lembut */
            color: #b91c1c;
        }

        /* table styling */
        .dataTables_filter {
            margin-bottom: 15px;
        }
        #akun-siswa thead tr th,
        #absensiswa thead tr th,
        #data-guru thead tr th{
            background-color: #2563EB;
            border: #2563EB;
        }
    </style>
    @stack('style')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Toggle Button untuk Mobile -->


        <!-- Overlay untuk Mobile -->
        <div class="sidebar-overlay"></div>

        <!-- Sidebar -->
        <aside class="app-sidebar bg-body-secondary {{$sidebarShow ?? 'd-block'}}">
            <div class="flex-shrink-0 p-3 bg-white d-flex flex-column h-100">
                <div class="sidebar-brand">
                    <a href="/" class="brand-link">
                        <img src="{{ asset('img/Prima Score.png') }}" alt="Logo" class="opacity-75 brand-image" style="width: 150px;">
                    </a>
                </div>
                <div class="sidebar-content">
                    <ul class="mb-auto nav nav-pills flex-column">
                        {{-- <li class="nav-item">
                            <button class="rounded btn btn-toggle align-items-center collapsed w-100 text-start"
                                data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                                Home
                            </button>
                            <div class="collapse show" id="home-collapse">
                                <ul class="pb-1 btn-toggle-nav list-unstyled fw-normal small ms-3 nav-treewiew">
                                    <li><a href="#" class="py-1 rounded link-dark d-block">Overview</a></li>
                                    <li><a href="#" class="py-1 rounded link-dark d-block">Updates</a></li>
                                    <li><a href="#" class="py-1 rounded link-dark d-block">Reports</a></li>
                                </ul>
                            </div>
                        </li> --}}


                        @hasanyrole('admin')
                        <li class="nav-item">
                            <a class="rounded navbar navbar-light btn ps-3 link-dark align-items-center w-100 text-start crazy-nav {{Route::is('home') ? 'bg-active' : ''}}"
                            href="{{route('home')}}">
                                Dashboard
                            </a>
                        </li>
                        @endhasanyrole

                        @hasanyrole('admin|scanner')
                        <li class="nav-item">
                            <a class="rounded navbar navbar-light btn ps-3 link-dark align-items-center w-100 text-start crazy-nav {{Route::is('scanner') ? 'bg-active' : ''}}"
                           href="{{route('scanner')}}">
                                Scanner
                            </a>
                        </li>
                        @endhasanyrole

                        @php
                            $dataPage = Route::is('data.students', 'data.students.account', 'data.absen', 'data.guru.index');
                        @endphp

                        <li>
                            <button class="rounded btn btn-toggle align-items-center collapsed w-100 text-start {{$dataPage ? 'bg-active' : ''}}"
                            data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="{{$dataPage ? 'true' : 'false'}}">
                            Data
                            </button>
                            <div class="collapse {{$dataPage ? 'show' : ''}}" id="dashboard-collapse">
                                <ul class="pb-1 btn-toggle-nav list-unstyled fw-normal small ms-3 menu-open">
                                    @hasanyrole('admin|teacher')
                                    <li class=" {{Route::is('data.absen') ? 'sub-nav-active' : ''}}"><a href="{{route('data.absen')}}" class="py-1 rounded d-block {{Route::is('data.absen') ? 'sub-nav-active' : ''}}">Riwayat Absen</a></li>
                                    <li class=" {{Route::is('data.students.account') ? 'sub-nav-active' : ''}}"><a href="{{route('data.students.account')}}" class="py-1 rounded d-block {{Route::is('data.students.account') ? 'sub-nav-active' : ''}}">Akun Siswa</a></li>
                                    @endhasanyrole
                                    <li class=" {{Route::is('data.guru.index') ? 'sub-nav-active' : ''}}"><a href="{{route('data.guru.index')}}" class="py-1 rounded d-block {{Route::is('data.guru.index') ? 'sub-nav-active' : ''}}">Akun Guru</a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="my-3 border-top"></li>

                        @php
                            $profilePage = Route::is('profile.index');
                        @endphp

                        <li>
                            <button class="rounded btn btn-toggle align-items-center collapsed w-100 text-start {{$profilePage ? 'bg-active' : ''}}"
                                data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                                Account
                            </button>
                            <div class="collapse {{Route::is('profile.index') ? 'show' : ''}}" id="account-collapse">
                                <ul class="pb-1 btn-toggle-nav list-unstyled fw-normal small ms-3">
                                    <li class=" {{Route::is('profile.index') ? 'sub-nav-active' : ''}}"><a href="{{route('profile.index')}}" class="py-1 rounded link-dark d-block {{Route::is('profile.index') ? 'sub-nav-active' : ''}}">Profile</a></li>
                                    <li class="btn-toggle-nav logout">
                                        <button class="btn btn-outline-danger mt-1" id="logout">Logout</button>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <div class="app-main">
            <div class="p-3 d-flex flex-column flex-grow-1">
                <div class="d-flex">
                    <button class="sidebar-toggle" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-menu">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <div class="app-content-header">
                        <h2 style="color: #303030;" class="{{$pageTitleName ?? 'd-block'}}">{{$pageTitleName ?? ''}}</h2>
                    </div>
                </div>
                <div class="p-3 rounded shadow-sm app-content">
                    {{$slot}}
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>

    <script>
        // Fungsi untuk toggle sidebar di mode mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.app-sidebar');
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const overlay = document.querySelector('.sidebar-overlay');
            const body = document.body;

            // Toggle sidebar ketika tombol diklik
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                body.classList.toggle('sidebar-open');
            });

            // Tutup sidebar ketika overlay diklik
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                body.classList.remove('sidebar-open');
            });

            // Tutup sidebar ketika item menu diklik di mode mobile
            if (window.innerWidth < 992) {
                const navLinks = document.querySelectorAll('.nav-link, .btn-toggle-nav a');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                        body.classList.remove('sidebar-open');
                    });
                });
            }

            // Responsif ketika ukuran window berubah
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    body.classList.remove('sidebar-open');
                }
            });

            // Logout function

        });
    </script>
    <script>
        $('#logout').on('click', () => {
            const logoutConfirm = confirm('Apakah ingin Logout?')
            if(logoutConfirm){
                $.ajax({
                    type: 'POST',
                    url: '{{route('logout')}}',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        window.location.href = "{{route('login')}}"
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // This function is executed if the request fails
                        console.error("Error:", textStatus, errorThrown);
                    }
                });
            }
        });
    </script>
    @stack('script')
</body>

</html>
