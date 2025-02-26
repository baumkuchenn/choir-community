@extends('layouts.main')

@section('content')
<div class="container">
    <h3 class="fw-bold">Informasi Pribadi</h3>
    <div class="row g-3 ">
        <div class="col-sm-6">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" placeholder="" value="{{ $user->name }}" required>
            <div class="invalid-feedback">
                Valid first name is required.
            </div>
        </div>

        <div class="col-sm-6">
            <label for="noHP" class="form-label">Nomor Handphone</label>
            <input type="text" class="form-control" id="noHP" placeholder="" value="{{ $user->no_handphone }}" required>
            <div class="invalid-feedback">
                Valid last name is required.
            </div>
        </div>

        <div class="col-sm-6">
            <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
            <input type="text" class="form-control" id="jenisKelamin" placeholder="" value="{{ $user->jenis_kelamin }}" required>
            <div class="invalid-feedback">
                Valid first name is required.
            </div>
        </div>

        <div class="col-sm-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" placeholder="" value="{{ $user->email }}">
            <div class="invalid-feedback">
                Please enter a valid email address for shipping updates.
            </div>
        </div>
    </div>
    <h3 class="mt-3 fw-bold">Informasi Tiket yang Dibeli</h3>
    <div class="row mt-3">
        <div class="col-12 col-lg-8">
            <img src="{{ $concert->gambar }}" style="width: 100%; max-height: 360px;">
        </div>
        <div class="col-12 col-lg-4">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="fw-bold">{{ $concert->nama }}</h4>
                    <div class="mt-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-calendar-days fa-fw fs-5"></i>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($concert->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                        </div>

                        <div class="mt-2 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-clock fa-fw fs-5"></i>
                            <p class="mb-0">Open Gate: {{ \Carbon\Carbon::parse($concert->jam_mulai)->format('H:i') }} WIB</p>
                        </div>

                        <div class="mt-2 d-flex gap-2">
                            <i class="fa-solid fa-location-dot fa-fw fs-5"></i>
                            <p class="mb-0">{{ $concert->lokasi }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <img src="{{ $concert->logo }}" style="width: 40px; height: 40px">
                        <div>
                            <p class="mb-0">Diselenggarakan oleh</p>
                            <p class="mb-0 fw-bold">{{ $concert->penyelenggara }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 col-lg-8">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="fw-bold">{{ $concert->nama }}</h4>
                    <div class="mt-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-calendar-days fa-fw fs-5"></i>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($concert->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                        </div>

                        <div class="mt-2 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-clock fa-fw fs-5"></i>
                            <p class="mb-0">Open Gate: {{ \Carbon\Carbon::parse($concert->jam_mulai)->format('H:i') }} WIB</p>
                        </div>

                        <div class="mt-2 d-flex gap-2">
                            <i class="fa-solid fa-location-dot fa-fw fs-5"></i>
                            <p class="mb-0">{{ $concert->lokasi }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <img src="{{ $concert->logo }}" style="width: 40px; height: 40px">
                        <div>
                            <p class="mb-0">Diselenggarakan oleh</p>
                            <p class="mb-0 fw-bold">{{ $concert->penyelenggara }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection