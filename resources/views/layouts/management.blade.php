<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="{{asset('js/color-modes.js')}}"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choir Community</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="{{asset('dist/css/bootstrap.min.css')}}" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }
    </style>

    <link href="{{asset('css/main.css')}}" rel="stylesheet">
</head>

<body>
    <header class="fixed-top">
        <nav class="navbar navbar-expand-md py-0 bg-body-secondary border-bottom">
            <div class="container d-flex flex-wrap">
                <ul class="nav me-auto">
                </ul>
                <ul class="nav">
                    <li class="nav-item"><a href="{{ route('eticket.index') }}" class="nav-link link-body-emphasis px-2">E-ticketing</a></li>
                    <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">Forum</a></li>
                    <li class="nav-item"><a href="{{ route('management.index') }}" class="nav-link link-body-emphasis px-2">Manajemen Komunitas</a></li>
                </ul>
            </div>
        </nav>
        <nav class="navbar navbar-expand-lg p-3 mb-4 bg-body border-bottom">
            <div class="container">
                <!-- Title -->
                <a href="{{ route('management.index') }}" class="navbar-brand"><b>Choir Community</b></a>

                <div class="d-flex align-items-center ms-auto">
                    <!-- Hamburger Icon -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <!-- Full Navbar Content (Large Screens) -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <div class="d-flex flex-wrap align-items-center text-end ms-auto">
                        @if(Gate::check('akses-event') || Gate::check('akses-member') || Gate::check('akses-roles') || Gate::check('akses-eticket') || Gate::allows('akses-event-panitia') || Gate::allows('akses-eticket-panitia'))
                            <div class="flex-shrink-0 dropdown">
                                <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle me-2 pe-2" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-list-check"></i> Manajemen
                                </a>
                                <ul class="dropdown-menu text-small shadow">
                                @if(Gate::allows('akses-event') || Gate::allows('akses-eticket') || Gate::allows('akses-event-panitia') || Gate::allows('akses-eticket-panitia'))
                                    <li><a class="dropdown-item" href="{{ route('events.index') }}">Manajemen Kegiatan</a></li>
                                @endif
                                @can('akses-member')
                                    <li><a class="dropdown-item" href="{{ route('members.index') }}">Manajemen Anggota</a></li>
                                @endcan
                                @can('akses-roles')
                                    <li><a class="dropdown-item" href="{{ route('roles.index') }}">Manajemen Roles</a></li>
                                @endcan
                                </ul>
                            </div>
                        @endif
                        <a href="{{ route('management.calendar.index') }}" class="btn me-2">
                            <i class="fa-solid fa-calendar-days"></i> Kalender
                        </a>
                        <a href="{{ route('management.notification') }}" class="btn me-2">
                            <i class="fa-solid fa-bell"></i> Notifikasi
                        </a>
                        @can('akses-admin')
                        <a href="{{ route('choir.profile', Auth::user()->members->first()->choirs_id) }}" class="btn me-2">
                            <i class="fa-solid fa-users"></i> Profil Komunitas
                        </a>
                        @endcan
                        <div class="flex-shrink-0 dropdown">
                            <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                                <img src="https://github.com/mdo.png" alt="Profile" width="32" height="32" class="rounded-circle">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu text-small shadow">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                                <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Offcanvas Sidebar for Small Screens -->
        <div class="offcanvas offcanvas-start" id="mobileMenu">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <div class="text-center">
                    <img src="https://github.com/mdo.png" alt="Profile" width="50" height="50" class="rounded-circle mb-2">
                    <p class="mb-3"><b>{{ Auth::user()->name }}</b></p>
                </div>
                @if(Gate::check('akses-event') || Gate::check('akses-member') || Gate::check('akses-roles') || Gate::allows('akses-event-panitia') || Gate::allows('akses-eticket-panitia'))
                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none text-secondary w-100 mb-2 text-start d-flex align-items-center gap-2 p-2 dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-list-check" style="width: 20px; text-align: center;"></i> Manajemen
                        </button>
                        <ul class="dropdown-menu text-small shadow w-100">
                        @if(Gate::allows('akses-event') || Gate::allows('akses-eticket') || Gate::allows('akses-event-panitia') || Gate::allows('akses-eticket-panitia'))
                            <li><a class="dropdown-item" href="{{ route('events.index') }}">Manajemen Kegiatan</a></li>
                        @endif
                        @can('akses-member')
                            <li><a class="dropdown-item" href="{{ route('members.index') }}">Manajemen Anggota</a></li>
                        @endcan
                        @can('akses-roles')
                            <li><a class="dropdown-item" href="{{ route('roles.index') }}">Manajemen Roles</a></li>
                        @endcan
                        </ul>
                    </div>
                @endif
                <a href="{{ route('management.calendar.index') }}" class="btn btn-link text-decoration-none text-secondary w-100 mb-2 text-start d-flex align-items-center gap-2 p-2">
                    <i class="fa-solid fa-calendar-days" style="width: 20px; text-align: center;"></i> Kalender
                </a>
                <a href="{{ route('management.notification') }}" class="btn btn-link text-decoration-none text-secondary w-100 mb-2 text-start d-flex align-items-center gap-2 p-2">
                    <i class="fa-solid fa-bell" style="width: 20px; text-align: center;"></i> Notifikasi
                </a>
                @can('akses-admin')
                <a href="{{ route('choir.profile', Auth::user()->members->first()->choirs_id) }}" class="btn btn-link text-decoration-none text-secondary w-100 mb-2 text-start d-flex align-items-center gap-2 p-2">
                    <i class="fa-solid fa-users" style="width: 20px; text-align: center;"></i> Profil Komunitas
                </a>
                @endcan
                <hr>
                <a href="{{ route('profile.edit') }}" class="btn btn-link text-decoration-none text-secondary w-100 mb-2 text-start d-flex align-items-center gap-2 p-2">
                    <i class="fa-solid fa-user" style="width: 20px; text-align: center;"></i> Profil
                </a>
                <a href="#" class="btn btn-link text-decoration-none text-secondary w-100 mb-2 text-start d-flex align-items-center gap-2 p-2">
                    <i class="fa-solid fa-gear" style="width: 20px; text-align: center;"></i> Pengaturan
                </a>
                <hr>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none text-danger w-100 mb-2 text-start d-flex align-items-center gap-2 p-2">
                        <i class="fa-solid fa-right-from-bracket" style="width: 20px; text-align: center;"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>
    <main>
        @yield('content')
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus <span id="deleteItemName"></span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{asset('dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    @yield('js')
    @stack('js')
    <script>
        //Button hapus
        document.querySelectorAll('.deleteBtn').forEach(button => {
            button.addEventListener('click', function() {
                let itemName = this.dataset.name;
                let itemAction = this.dataset.action;
                document.getElementById('deleteItemName').textContent = itemName;
                let deleteForm = document.getElementById('deleteForm');
                deleteForm.action = itemAction;
                let modalElement = document.getElementById('deleteConfirmModal');
                let deleteModal = new bootstrap.Modal(modalElement);
                deleteModal.show();
            });
        });
    </script>
</body>

</html>