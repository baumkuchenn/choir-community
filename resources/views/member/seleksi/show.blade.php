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
        <h3 class="mb-3 fw-bold text-center">Detail Seleksi Anggota Baru Komunitas</h3>
        <h5 class="fw-medium text-center">
            @if ($seleksi->tanggal_mulai != $seleksi->tanggal_selesai)
                {{ \Carbon\Carbon::parse($seleksi->tanggal_mulai)->translatedFormat('d') }} - 
                {{ \Carbon\Carbon::parse($seleksi->tanggal_selesai)->translatedFormat('d F Y') }}
            @else
                {{ \Carbon\Carbon::parse($seleksi->tanggal_mulai)->translatedFormat('d F Y') }}
            @endif
        </h5>
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

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="pendaftaran_terakhir" class="form-label">Tanggal Pendaftaran Terakhir</label>
                        <input type="date" class="form-control" id="pendaftaran_terakhir" name="pendaftaran_terakhir" placeholder="" value="{{ old('pendaftaran_terakhir', $seleksi->pendaftaran_terakhir) }}" required>
                        @error('pendaftaran_terakhir')
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
                    <button type="button" class="btn btn-primary mb-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                        + Tambah Anggota Baru
                    </button>
                    <table id="pendaftarTable" class="table table-bordered shadow text-center">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center">Nama Lengkap</th>
                                <th class="text-center">Nomor Handphone</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Jenis Kelamin</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendaftar as $item)
                                <tr>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->user->no_handphone }}</td>
                                    <td>{{ $item->user->email }}</td>
                                    <td>{{ $item->user->jenis_kelamin }}</td>
                                    <td>
                                        @if ($item->nilais->isEmpty())
                                            <a href="{{ route('seleksi.wawancara', ['seleksi' => $item->seleksi->id, 'user' => $item->user->id]) }}" class="btn btn-primary">Lihat Detail</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="hasil" role="tabpanel">
                <div class="mb-3">
                    <h5>Hasil Seleksi Penyanyi Baru</h5>
                    <table id="hasilTable" class="table table-bordered shadow text-center">
                        <thead class="text-center">
                            <tr class="bg-primary">
                                <th>Nama Lengkap</th>
                                <th>Nomor Handphone</th>
                                <th>Suara</th>
                                <th>Nilai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasil as $item)
                                <tr>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->user->no_handphone }}</td>
                                    <td>
                                        @if($item->kategori_suara == 'sopran_1')
                                            Sopran 1
                                        @elseif($item->kategori_suara == 'sopran_2')
                                            Sopran 2
                                        @elseif($item->kategori_suara == 'alto_1')
                                            Alto 1
                                        @elseif($item->kategori_suara == 'alto_2')
                                            Alto 2
                                        @elseif($item->kategori_suara == 'tenor_1')
                                            Tenor 1
                                        @elseif($item->kategori_suara == 'tenor_2')
                                            Tenor 2
                                        @elseif($item->kategori_suara == 'bass_1')
                                            Bass 1
                                        @elseif($item->kategori_suara == 'bass_2')
                                            Bass 2
                                        @endif
                                    </td>
                                    <td>{{ $item->nilais->sum('pivot.nilai') }}</td>
                                    <td>
                                        @if($item->lolos == 'belum')
                                            Pending
                                        @elseif($item->lolos == 'ya')
                                            Diterima
                                        @elseif($item->lolos == 'tidak')
                                            Ditolak
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('seleksi.wawancara', ['seleksi' => $item->seleksi->id, 'user' => $item->user->id]) }}" class="btn btn-primary">Lihat Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('member.seleksi.modal.form-create')
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

        //Modal tambah pendaftar
        $('#addMemberModal').on('shown.bs.modal', function () {
            $('#user_id').select2({
                placeholder: 'Cari Pengguna...',
                dropdownParent: $('#addMemberModal'),
                ajax: {
                    url: '{{ route("members.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            });
        });

        //Datatable pendaftar dan hasil
        $(document).ready(function() {
            $('#pendaftarTable').DataTable({
                "language": {
                    "emptyTable": "Belum ada pendaftar"
                }
            }); 
            $('#hasilTable').DataTable({
                "language": {
                    "emptyTable": "Belum ada pendaftar"
                }
            }); 
        });
    });
</script>
@endsection