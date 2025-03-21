@extends('layouts.management')

@section('content')
<div class="container">
    @if(session('status') === 'success')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="col-md-11 col-lg-11 mx-auto">
        <h2 class="mb-3 fw-bold text-center">Daftar Kegiatan Komunitas</h2>
        <a href="{{ route('events.create') }}" class="btn btn-primary mb-3 fw-bold" >+ Tambah Kegiatan</a>

        <h5 class="mb-3 fw-bold">Kegiatan Kedepannya</h5>
        <hr>
        @if ($eventSelanjutnya->isEmpty())
            <p class="text-center">Komunitas ini belum memiliki kegiatan.</p>
        @else
            @foreach ($eventSelanjutnya as $event)
                <div class="card shadow border-0">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="col-md-7">
                                    <h5 class="fw-bold">{{ $event->nama }}</h5>
                                    <div class="mt-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-calendar-days fa-fw fs-5"></i>
                                            <p class="mb-0">{{ \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                                        </div>
                                        <div class="mt-2 d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-clock fa-fw fs-5"></i>
                                            <p class="mb-0">Open Gate: {{ \Carbon\Carbon::parse($event->jam_mulai)->format('H:i') }} WIB</p>
                                        </div>
                                        <div class="mt-2 d-flex gap-2">
                                            <i class="fa-solid fa-location-dot fa-fw fs-5"></i>
                                            <p class="mb-0">{{ $event->lokasi }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 text-end d-none d-md-block">
                                    <a href="events/{{ $event->id }}" class="btn btn-primary">Lihat Detail</a>
                                    <button class="btn btn-outline-danger deleteBtn" data-name="kegiatan {{ $event->nama }}" data-action="{{ route('events.destroy', $event->id) }}">Hapus</button>
                                </div>
                            </div>
                            <div class="col-12 text-end d-block d-md-none">
                                <a href="events/{{ $event->id }}" class="btn btn-primary">Lihat Detail</a>
                                <button class="btn btn-outline-danger deleteBtn" data-name="kegiatan {{ $event->nama }}" data-action="{{ route('events.destroy', $event->id) }}">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <h5 class="mt-4 mb-3 fw-bold">Kegiatan Lalu</h5>
        <hr>
        @if ($eventLalu->isEmpty())
            <p class="text-center">Komunitas ini belum memiliki kegiatan.</p>
        @else
            @foreach ($eventLalu as $event)
                <div class="card shadow border-0 mb-3">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="col-md-7">
                                    <h5 class="fw-bold">{{ $event->nama }}</h5>
                                    <div class="mt-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-calendar-days fa-fw fs-5"></i>
                                            <p class="mb-0">{{ \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                                        </div>
                                        <div class="mt-2 d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-clock fa-fw fs-5"></i>
                                            <p class="mb-0">Open Gate: {{ \Carbon\Carbon::parse($event->jam_mulai)->format('H:i') }} WIB</p>
                                        </div>
                                        <div class="mt-2 d-flex gap-2">
                                            <i class="fa-solid fa-location-dot fa-fw fs-5"></i>
                                            <p class="mb-0">{{ $event->lokasi }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 text-end d-none d-md-block">
                                    <a href="events/{{ $event->id }}" class="btn btn-primary">Lihat Detail</a>
                                    <button class="btn btn-outline-danger deleteBtn" data-name="kegiatan {{ $event->nama }}" data-action="{{ route('events.destroy', $event->id) }}">Hapus</button>
                                </div>
                            </div>
                            <div class="col-12 text-end d-block d-md-none">
                                <a href="events/{{ $event->id }}" class="btn btn-primary">Lihat Detail</a>
                                <button class="btn btn-outline-danger deleteBtn" data-name="kegiatan {{ $event->nama }}" data-action="{{ route('events.destroy', $event->id) }}">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection