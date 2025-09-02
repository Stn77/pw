<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title ?? config('app.name')}}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
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
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 9999;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 6px 12px;
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

        /* Sidebar styling */
        .sidebar-brand {
            padding: 0.5rem 0 1rem 0 ;
            /* padding-bottom: 0.5rem; */
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        .sidebar-content {
            height: calc(100vh - 80px);
            overflow-y: auto;
            padding: 0 0 1rem 0;
        }

        .nav-pills .nav-link {
            border-radius: 0;
            padding: 0.75rem 1rem;
        }

        .btn-toggle {
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: rgba(0, 0, 0, 0.65);
            background-color: transparent;
            border: 0;
            width: 100%;
            text-align: left;
        }

        .btn-toggle:hover,
        .btn-toggle:focus {
            color: rgba(0, 0, 0, 0.85);
            background-color: #f8f9fa;
        }

        .btn-toggle::after {
            width: 1.25em;
            line-height: 0;
            content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%280,0,0,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
            transition: transform 0.35s ease;
            transform-origin: 0.5em 50%;
            float: right;
        }

        .btn-toggle[aria-expanded="true"] {
            color: rgba(0, 0, 0, 0.85);
        }

        .btn-toggle[aria-expanded="true"]::after {
            transform: rotate(90deg);
        }

        .btn-toggle-nav a ,.logout{
            padding: 0.5rem 1.5rem;
            margin-top: 0.125rem;
            margin-left: 1.25rem;
            font-size: 0.875rem;
            color: rgba(0, 0, 0, 0.65);
            text-decoration: none;
        }

        .logout button{
            width: 100%;
        }

        .btn-toggle-nav a:hover,
        .btn-toggle-nav a:focus {
            background-color: #f8f9fa;
            color: rgba(0, 0, 0, 0.85);
        }
    </style>
    @stack('style')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Toggle Button untuk Mobile -->
        <button class="sidebar-toggle" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <!-- Overlay untuk Mobile -->
        <div class="sidebar-overlay"></div>

        <!-- Sidebar -->
        <aside class="app-sidebar bg-body-secondary {{$sidebarShow ? 'd-block' : 'd-none'}}">
            <div class="flex-shrink-0 p-3 bg-white d-flex flex-column h-100">
                <div class="sidebar-brand">
                    <a href="/" class="brand-link">
                        <img src="{{ asset('img/Prima Pay.png') }}" alt="Logo" class="opacity-75 brand-image" style="width: 150px;">
                    </a>
                </div>
                <div class="sidebar-content">
                    <ul class="mb-auto nav nav-pills flex-column">
                        <li class="nav-item">
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
                        </li>

                        <li>
                            <button class="rounded btn btn-toggle align-items-center collapsed w-100 text-start"
                                data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                                Dashboard
                            </button>
                            <div class="collapse" id="dashboard-collapse">
                                <ul class="pb-1 btn-toggle-nav list-unstyled fw-normal small ms-3 menu-open">
                                    <li><a href="#" class="py-1 rounded link-dark d-block">Overview</a></li>
                                    <li><a href="#" class="py-1 rounded link-dark d-block">Weekly</a></li>
                                    <li><a href="#" class="py-1 rounded link-dark d-block">Monthly</a></li>
                                    <li><a href="#" class="py-1 rounded link-dark d-block">Annually</a></li>
                                </ul>
                            </div>
                        </li>

                        <li>
                            <button class="rounded btn btn-toggle align-items-center collapsed w-100 text-start"
                                data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                                Orders
                            </button>
                            <div class="collapse" id="orders-collapse">
                                <ul class="pb-1 btn-toggle-nav list-unstyled fw-normal small ms-3">
                                    <li><a href="#" class="py-1 rounded link-dark d-block">New</a></li>
                                    <li><a href="#" class="py-1 rounded link-dark d-block">Processed</a></li>
                                    <li><a href="#" class="py-1 rounded link-dark d-block">Shipped</a></li>
                                    <li><a href="#" class="py-1 rounded link-dark d-block">Returned</a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="my-3 border-top"></li>

                        <li>
                            <button class="rounded btn btn-toggle align-items-center collapsed w-100 text-start"
                                data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                                Account
                            </button>
                            <div class="collapse" id="account-collapse">
                                <ul class="pb-1 btn-toggle-nav list-unstyled fw-normal small ms-3">
                                    <li><a href="#" class="py-1 rounded link-dark d-block">New...</a></li>
                                    <li><a href="#" class="py-1 rounded link-dark d-block">Profile</a></li>
                                    <li><a href="#" class="py-1 rounded link-dark d-block">Settings</a></li>
                                    <li>
                                        <form class="logout" method="POST" action="{{ route('logout') }}" style="width: max-content; ">
                                            @csrf
                                            <button type="submit" role="button" id="logoutBtn" class="p-0 m-0 align-baseline btn btn-link">Logout</button>
                                        </form>
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
                <div class="">
                    <div class="app-content-header">
                        <h1 style="color: #303030;" class="{{$pageTitleName ? 'd-block' :'d-none'}}">{{$pageTitleName ?? ''}}</h1>
                    </div>
                </div>
                <div class="p-3 bg-white rounded shadow-sm app-content">
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
    @stack('script')
</body>

</html>
