<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="{{asset('js/color-modes.js')}}"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choir Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous">
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
        <div class="p-3 mb-4 bg-body border-bottom">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-lg-5">
                    <a href="{{ route('management.index') }}" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none p-1">
                        <b> Choir Management </b>
                    </a>

                    <div class="d-flex flex-wrap align-items-center text-end">
                        <div class="flex-shrink-0 dropdown">
                            <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle me-2 pe-2" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-list-check"></i>
                                Manajemen
                            </a>
                            <ul class="dropdown-menu text-small shadow">
                                <li><a class="dropdown-item" href="{{ route('events.index') }}">Manajemen Kegiatan</a></li>
                                <li><a class="dropdown-item" href="{{ route('members.index') }}">Manajemen Anggota</a></li>
                                <li><a class="dropdown-item" href="{{ route('roles.index') }}">Manajemen Roles</a></li>
                            </ul>
                        </div>
                        <a href="{{ route('management.calendar') }}" class="btn me-2">
                            <i class="fa-solid fa-calendar-days"></i>
                            Kalender
                        </a>
                        <a href="{{ route('management.notification') }}" class="btn me-2">
                            <i class="fa-solid fa-bell"></i>
                            Notifikasi
                        </a>
                        <div class="flex-shrink-0 dropdown">
                            <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu text-small shadow">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                                <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
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
    <script src="{{asset('dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    @yield('js')
    <script>
        //Button hapus
        document.querySelectorAll('.deleteBtn').forEach(button => {
            button.addEventListener('click', function() {
                let itemId = this.dataset.id;
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