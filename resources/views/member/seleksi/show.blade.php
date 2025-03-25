@extends('layouts.management')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="col-md-11 col-lg-11 mx-auto">
        <h2 class="mb-3 fw-bold text-center">Detail Seleksi Anggota Baru Komunitas</h2>
        <a href="{{ $backUrl ?? route('seleksi.index') }}" class="btn btn-outline-primary">Kembali</a>
        <nav class="navbar">
            <div class="d-flex flex-nowrap overflow-auto w-100 hide-scrollbar border-bottom border-black" id="navbarNav">
                <div class="navbar-nav fs-5 flex-row" role="tablist">
                    <a class="nav-link active px-3" data-bs-toggle="tab" href="#form-detail" role="tab" aria-controls="detail" aria-selected="true">Detail</a>
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#pendaftar" role="tab" aria-controls="pendaftar" aria-selected="false">Pendaftar</a>
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#hasil" role="tab" aria-controls="hasil" aria-selected="false">Hasil</a>
                </div>
            </div>
        </nav>

        <style>
            .hide-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }
        </style>

        <div class="tab-content">
            <form method="POST" action="{{ route('seleksi.update', $seleksi->id) }}" class="tab-pane fade show active mb-5" id="form-detail" role="tabpanel">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-6">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" placeholder="" value="{{ old('tanggal_mulai', $seleksi->tanggal_mulai) }}" required>
                        @error('tanggal_mulai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" placeholder="" value="{{ old('tanggal_selesai', $seleksi->tanggal_selesai) }}" required>
                        @error('tanggal_selesai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" placeholder="" value="{{ old('jam_mulai', $seleksi->jam_mulai) }}" required>
                        @error('jam_mulai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" placeholder="" value="{{ old('jam_selesai', $seleksi->jam_selesai) }}" required>
                        @error('jam_selesai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="" value="{{ old('lokasi', $seleksi->lokasi) }}" required>
                        @error('lokasi')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="fixed-bottom bg-body-secondary p-3 border-top text-end">
                    <button class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
            <div class="tab-pane fade" id="pendaftar" role="tabpanel">
                <div class="mb-3">
                    <h5>Daftar Seleksi Penyanyi Baru</h5>
                    <table id="pendaftarTable" class="table table-bordered shadow text-center">
                        <thead class="text-center">
                            <tr class="bg-primary">
                                <th>Nama Lengkap</th>
                                <th>Nomor Handphone</th>
                                <th>Email</th>
                                <th>Jenis Kelamin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($pendaftar->isNotEmpty())
                                @foreach($pendaftar as $item)
                                    <tr>
                                        <td>{{ $pendaftar->user->name }}</td>
                                        <td>{{ $pendaftar->user->no_handphone }}</td>
                                        <td>{{ $pendaftar->user->email }}</td>
                                        <td>{{ $pendaftar->user->jenis_kelamin }}</td>
                                        <td>
                                            <form action="{{ route('events.payment', $pendaftar->id) }}" method="POST" enctype="multipart/form-data" class="mb-0">
                                                @csrf
                                                <button class="btn btn-primary">Lihat Detail</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Belum ada pendaftar</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="hasil" role="tabpanel">
                <div class="mb-3">
                    <h5>Hasil Seleksi Penyanyi Baru</h5>
                    <table id="pendaftarTable" class="table table-bordered shadow text-center">
                        <thead class="text-center">
                            <tr class="bg-primary">
                                <th>Nama Lengkap</th>
                                <th>Nomor Handphone</th>
                                <th>Email</th>
                                <th>Jenis Kelamin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($pendaftar->isNotEmpty())
                                @foreach($pendaftar as $item)
                                    <tr>
                                        <td>{{ $pendaftar->user->name }}</td>
                                        <td>{{ $pendaftar->user->no_handphone }}</td>
                                        <td>{{ $pendaftar->user->email }}</td>
                                        <td>{{ $pendaftar->user->jenis_kelamin }}</td>
                                        <td>
                                            <form action="{{ route('events.payment', $pendaftar->id) }}" method="POST" enctype="multipart/form-data" class="mb-0">
                                                @csrf
                                                <button class="btn btn-primary">Lihat Detail</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Belum ada pendaftar</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        //Font Bold Navbar
        let navLinks = document.querySelectorAll(".navbar-nav .nav-link");
        function updateActiveTab() {
            navLinks.forEach(link => link.classList.remove("fw-bold"));
            let activeTab = document.querySelector(".navbar-nav .nav-link.active");
            if (activeTab) {
                activeTab.classList.add("fw-bold");
            }
        }
        
        updateActiveTab();
        navLinks.forEach(link => {
            link.addEventListener("shown.bs.tab", function() {
                updateActiveTab();
            });
        });
    });
</script>
@endsection