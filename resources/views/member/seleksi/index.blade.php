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
        <a href="{{ $backUrl ?? route('members.index') }}" class="btn btn-outline-primary">Kembali</a>
        <h2 class="mb-3 fw-bold text-center">Daftar Seleksi Komunitas</h2>
        <a href="{{ route('seleksi.create') }}" class="btn btn-primary mb-3 fw-bold" >+ Tambah Seleksi</a>

        <h5 class="mb-3 fw-bold">Seleksi Kedepannya</h5>
        <hr>
        @if ($seleksiDepan->isEmpty())
            <p class="text-center">Komunitas ini belum pernah mengadakan seleksi anggota baru.</p>
        @else
            @foreach ($seleksiDepan as $item)
                <div class="card shadow border-0 mb-3">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="col-md-7">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-calendar-days fa-fw fs-5"></i>
                                        <p class="mb-0">
                                            @if ($item->tanggal_mulai != $item->tanggal_selesai)
                                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d') }} - 
                                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-clock fa-fw fs-5"></i>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }} WIB</p>
                                    </div>
                                    <div class="mt-2 d-flex gap-2">
                                        <i class="fa-solid fa-location-dot fa-fw fs-5"></i>
                                        <p class="mb-0">{{ $item->lokasi }}</p>
                                    </div>
                                </div>
                                <div class="col-md-5 text-end d-none d-md-block">
                                    <a href="seleksi/{{ $item->id }}" class="btn btn-primary">Lihat Detail</a>
                                    <button class="btn btn-outline-danger deleteBtn" data-name="seleksi tanggal {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') }}" data-action="{{ route('seleksi.destroy', $item->id) }}">Hapus</button>
                                </div>
                            </div>
                            <div class="col-12 text-end d-block d-md-none">
                                <a href="seleksi/{{ $item->id }}" class="btn btn-primary">Lihat Detail</a>
                                <button class="btn btn-outline-danger deleteBtn" data-name="seleksi tanggal {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') }}" data-action="{{ route('seleksi.destroy', $item->id) }}">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <h5 class="mt-4 mb-3 fw-bold">Seleksi Lalu</h5>
        <hr>
        @if ($seleksiLalu->isEmpty())
            <p class="text-center">Komunitas ini belum pernah mengadakan seleksi anggota baru.</p>
        @else
            @foreach ($seleksiLalu as $item)
                <div class="card shadow border-0 mb-3">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="col-md-7">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-calendar-days fa-fw fs-5"></i>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') }}</p>
                                    </div>
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-clock fa-fw fs-5"></i>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }} WIB</p>
                                    </div>
                                    <div class="mt-2 d-flex gap-2">
                                        <i class="fa-solid fa-location-dot fa-fw fs-5"></i>
                                        <p class="mb-0">{{ $item->lokasi }}</p>
                                    </div>
                                </div>
                                <div class="col-md-5 text-end d-none d-md-block">
                                    <a href="seleksi/{{ $item->id }}" class="btn btn-primary">Lihat Detail</a>
                                    <button class="btn btn-outline-danger deleteBtn" data-name="seleksi tanggal {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') }}" data-action="{{ route('seleksi.destroy', $item->id) }}">Hapus</button>
                                </div>
                            </div>
                            <div class="col-12 text-end d-block d-md-none">
                                <a href="seleksi/{{ $item->id }}" class="btn btn-primary">Lihat Detail</a>
                                <button class="btn btn-outline-danger deleteBtn" data-name="seleksi tanggal {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') }}" data-action="{{ route('seleksi.destroy', $item->id) }}">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection