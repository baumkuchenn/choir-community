@extends('layouts.management')

@section('content')
<div class="container">
    <a href="{{ route('choir.join') }}" class="btn btn-outline-primary">Kembali</a>
    <div class="row mt-3">
        <h3 class="fw-bold">{{ $seleksi->choir->nama }}</h3>
        <div class="col-12 col-lg-8">
            <img src="{{ asset('storage/'. $seleksi->choir->profil) }}" style="width: 100%; aspect-ratio: 16/9; object-fit: cover;">
        </div>
        <div class="col-12 col-lg-4">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="fw-bold">Informasi Seleksi</h4>
                    <div class="mt-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-calendar-days fa-fw fs-5"></i>
                            <p class="mb-0">
                                @if ($seleksi->tanggal_mulai != $seleksi->tanggal_selesai)
                                    {{ \Carbon\Carbon::parse($seleksi->tanggal_mulai)->translatedFormat('d') }} - 
                                    {{ \Carbon\Carbon::parse($seleksi->tanggal_selesai)->translatedFormat('d F Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($seleksi->tanggal_mulai)->translatedFormat('d F Y') }}
                                @endif
                            </p>
                        </div>

                        <div class="mt-2 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-clock fa-fw fs-5"></i>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($seleksi->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($seleksi->jam_selesai)->format('H:i') }} WIB</p>
                        </div>

                        <div class="mt-2 d-flex gap-2">
                            <i class="fa-solid fa-location-dot fa-fw fs-5"></i>
                            <p class="mb-0">{{ $seleksi->lokasi }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="fw-bold">Tentang {{ $seleksi->choir->nama }}</h5>
                    <p class="mt-1">{!! nl2br(e($seleksi->choir->deskripsi)) !!}</p>
                </div>
            </div>
        </div>
    </div>
    @if(!$pendaftar)
        <div class="row mt-3">
            <form action="{{ route('choir.register', $seleksi->id) }}" method="POST" enctype="multipart/form-data" class="mb-0 d-flex justify-content-center">
                @csrf
                <button class="btn btn-primary w-75 fw-bold">Daftar Seleksi</button>
            </form>
        </div>
    @endif
</div>
@endsection