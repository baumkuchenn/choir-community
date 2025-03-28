@extends('layouts.header-only')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="col-md-11 col-lg-11 mx-auto">
        <a href="{{ $backUrl ?? route('management.index') }}" class="btn btn-outline-primary mb-3">Kembali</a>
        
        <h5 class="mb-3 fw-bold">Seleksi yang diikuti</h5>
        <hr>
        @if ($daftar->isEmpty())
            <p class="text-center">Anda belum mendaftar seleksi anggota baru pada suatu komunitas.</p>
        @else
            @foreach ($daftar as $item)
                <div class="card shadow border-0 mb-3">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mt-2 d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/'. $item->seleksi->choir->logo) }}" style="width: 60px; height: 60px">
                                    <div>
                                        <h4 class="mb-0">{{ $item->seleksi->choir->nama }}</h4>
                                        <p class="mb-0">{{ $item->seleksi->choir->kota }}</p>
                                    </div>
                                </div>
                                <div class="col-md-5 text-end d-none d-md-block">
                                    <a href="join/detail/{{ $item->seleksi->id }}" class="btn btn-outline-primary">Lihat Detail</a>
                                </div>
                            </div>
                            <div class="col-12 text-end d-block d-md-none">
                                <a href="join/detail/{{ $item->seleksi->id }}" class="btn btn-outline-primary">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="d-flex justify-content-between align-items-end mt-4 mb-3">
            <h5 class="fw-bold">Daftar komunitas paduan suara</h5>
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Search..." autocomplete="off">
        </div>
        <hr>
        <div id="loading" class="text-center mt-2" style="display: none;">
            <span class="spinner-border text-primary" role="status"></span>
        </div>
        <div id="choirList"> 
            @include('choir.partials.choir_list', ['choir' => $choir])
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function fetchChoirs(page = 1, search = '') {
            document.getElementById('loading').style.display = 'block'; // Show loading animation

            fetch(`{{ route('choir.search') }}?search=${search}&page=${page}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('choirList').innerHTML = data;
                    document.getElementById('loading').style.display = 'none'; // Hide loading animation
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        // Search function
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let search = this.value;
            fetchChoirs(1, search);
        });

        // Handle pagination click
        document.addEventListener('click', function (e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                let page = new URL(e.target.href).searchParams.get("page");
                let search = document.getElementById('searchInput').value;
                fetchChoirs(page, search);
            }
        });
    });
</script>
@endsection