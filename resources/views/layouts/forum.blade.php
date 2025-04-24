<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="{{asset('js/color-modes.js')}}"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choir Community: Forum</title>
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
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="check2" viewBox="0 0 16 16">
            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
        </symbol>
        <symbol id="circle-half" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
        </symbol>
        <symbol id="moon-stars-fill" viewBox="0 0 16 16">
            <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />
            <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
        </symbol>
        <symbol id="sun-fill" viewBox="0 0 16 16">
            <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
        </symbol>
    </svg>

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
        <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center"
            id="bd-theme"
            type="button"
            aria-expanded="false"
            data-bs-toggle="dropdown"
            aria-label="Toggle theme (auto)">
            <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
                <use href="#circle-half"></use>
            </svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#sun-fill"></use>
                    </svg>
                    Light
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#moon-stars-fill"></use>
                    </svg>
                    Dark
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#circle-half"></use>
                    </svg>
                    Auto
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
        </ul>
    </div>

    <header class="fixed-top">
        <nav class="navbar navbar-expand-md py-0 bg-body-secondary border-bottom">
            <div class="container d-flex flex-wrap">
                <ul class="nav me-auto">
                </ul>
                <ul class="nav">
                    <li class="nav-item"><a href="{{ route('eticket.index') }}" class="nav-link link-body-emphasis px-2">E-ticketing</a></li>
                    <li class="nav-item"><a href="{{ route('forum.index') }}" class="nav-link link-body-emphasis px-2">Forum</a></li>
                    <li class="nav-item"><a href="{{ route('management.index') }}" class="nav-link link-body-emphasis px-2">Manajemen Komunitas</a></li>
                </ul>
            </div>
        </nav>

        <nav class="navbar navbar-expand-lg p-3 mb-4 bg-body border-bottom">
            <div class="container">
                <!-- Title -->
                <a href="/" class="navbar-brand"><b>Choir Community</b></a>

                <div class="d-flex align-items-center ms-auto">
                    <!-- Search Icon (Hidden on lg, Shown on sm & md) -->
                    <button class="btn d-lg-none me-2" id="searchButton">
                        <i class="fa fa-search"></i>
                    </button>

                    <!-- Hamburger Icon -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <!-- Search Bar (Hidden by Default, Shows on Icon Click) -->
                <div id="searchBar" class="d-none w-100 position-absolute top-0 start-0 p-3 bg-light shadow">
                    <form method="GET" action="{{ route('forum.search') }}">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="hidden" name="tab" value="posts">
                            <input type="search" class="form-control" name="search_input" value="{{ old('search_input') }}" placeholder="Cari forum disini" aria-label="Search">
                            <button class="btn btn-danger" id="closeSearch"><i class="fa fa-times"></i></button>
                        </div>
                    </form>
                </div>

                <!-- Full Navbar Content (Only Shown on Large Screens) -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <form method="GET" action="{{ route('forum.search') }}" class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-auto flex-grow-1 d-none d-lg-block" role="search">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="hidden" name="tab" value="posts">
                            <input type="search" class="form-control" name="search_input" value="{{ old('search_input') }}" placeholder="Cari forum disini" aria-label="Search">
                        </div>
                    </form>

                    <div class="d-flex align-items-center text-end ms-3">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('forum.notification') }}" class="btn position-relative me-2">
                                    <i class="fa-solid fa-bell fa-fw"></i> Notifikasi
                                    @php
                                        $unreadNotif = auth()->user()->unreadNotifications->where('data.tipe', 'forum')->count();
                                    @endphp

                                    @if($unreadNotif > 0)
                                        <span class="position-absolute translate-middle badge rounded-pill bg-primary" style="font-size: 0.65rem; top: 8px; left: 8px;">
                                            {{ $unreadNotif }}
                                        </span>
                                    @endif
                                </a>
                                <div class="dropdown">
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
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk</a>
                                <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                            @endauth
                        @endif
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
                @auth
                    <div class="text-center">
                        <img src="https://github.com/mdo.png" alt="Profile" width="50" height="50" class="rounded-circle mb-2">
                        <p class="mb-3"><b>{{ Auth::user()->name }}</b></p>
                    </div>
                    <a href="{{ route('forum.notification') }}" class="btn btn-link text-decoration-none text-secondary w-100 mb-2 text-start d-flex align-items-center gap-2 p-2">
                        <i class="fa-solid fa-bell fa-fw" style="width: 20px; text-align: center;"></i> Notifikasi
                    </a>
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
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">Daftar</a>
                @endauth
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
        <!-- Leave Confirmation Modal -->
        <div class="modal fade" id="confirmLeaveModal" tabindex="-1" aria-labelledby="confirmLeaveModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmLeaveModalLabel">Keluar dari Forum</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Apakah kamu yakin ingin keluar dari forum ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="keluarForm" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Keluar</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{asset('dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        document.getElementById("searchButton").addEventListener("click", function () {
            document.getElementById("searchBar").classList.remove("d-none");
        });
        document.getElementById("closeSearch").addEventListener("click", function () {
            document.getElementById("searchBar").classList.add("d-none");
        });
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
        //button keluar
        document.querySelectorAll('.btn-keluar').forEach(button => {
            button.addEventListener('click', function() {
                let itemAction = this.dataset.action;
                let keluarForm = document.getElementById('keluarForm');
                keluarForm.action = itemAction;
                let modalElement = document.getElementById('confirmLeaveModal');
                let keluarModal = new bootstrap.Modal(modalElement);
                keluarModal.show();
            });
        });
    </script>
    @yield('js')
</body>

</html>