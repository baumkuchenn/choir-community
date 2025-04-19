@extends('layouts.bootstrap-only')

@section('content')
<div class="container">
    <div class="row mt-3">
        <div class="col-12 col-lg-8">
            <img src="{{ asset('storage/' . $concert->gambar) }}" style="width: 100%; aspect-ratio: 16/9; object-fit: cover;">
        </div>
        <div class="col-12 col-lg-4">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="fw-bold">{{ $event->nama }}</h4>
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
                    <hr>
                    <div class="mt=2">
                        @foreach ($choirs as $choir)
                            <div class="mb-2 d-flex align-items-center gap-2">
                                <img src="{{ asset('storage/' . $choir->logo) }}" style="width: 40px; height: 40px">
                                <div>
                                    <p class="mb-0">Diselenggarakan oleh</p>
                                    <p class="mb-0 fw-bold">{{ $choir->nama }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <h4 class="fw-bold">Informasi Pesanan</h4>
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <p class="mb-0">Nama</p>
                        <p class="fw-bold">{{ $user->name }}</p>
                    </div>
                    <div class="col-6">
                        <p class="mb-0">Kode Invoice</p>
                        <p class="fw-bold">{{ $invoice->kode }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="mb-0">Tanggal Pembelian</p>
                        <p class="fw-bold">{{ $purchase->waktu_pembelian }} WIB</p>
                    </div>
                    <div class="col-6">
                        <p class="mb-0">Tiket Dibeli</p>
                        @foreach($purchaseDetails as $detail)
                            <p class="fw-bold">{{ $detail->nama }} {{ $detail->pivot->jumlah }} tiket</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="d-flex align-items-center justify-content-between pe-0 ps-0">
            @foreach($tickets as $ticket)
                <div class="card shadow col-6 col-md-4 col-lg-3">
                    <div class="card-body text-center">
                        <p class="mb-0 fw-bold">{{ $ticket->ticket_type->nama }} #{{ $ticket->number }}</p>
                        <hr>
                        <img src="{{ asset('storage/' . $ticket->barcode_image) }}" alt="Barcode" style="width: 100%;">
                        <p>{{ $ticket->barcode_code }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row mt-3">
        <h4 class="fw-bold">Syarat & Ketentuan</h4>
        <p>{!! nl2br(e($concert->syarat_ketentuan)) !!}</p>
    </div>
</div>
@endsection