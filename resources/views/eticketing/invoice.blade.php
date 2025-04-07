@extends('layouts.eticket')

@section('content')
<div class="container">
    <a href="{{ url()->previous() }}" class="btn btn-outline-primary">Kembali</a>
    <button class="btn btn-primary" onclick="printSection('print-invoice')">Print</button>
    <div id="print-invoice">
        <div class="row mt-3">
            <div class="col-12 col-lg-8">
                <img src="{{ asset('storage/' . $concerts->gambar) }}" style="width: 100%; aspect-ratio: 16/9; object-fit: cover;">
            </div>
            <div class="col-12 col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="fw-bold">{{ $events->nama }}</h4>
                        <div class="mt-4">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-calendar-days fa-fw fs-5"></i>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($events->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                            </div>

                            <div class="mt-2 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-clock fa-fw fs-5"></i>
                                <p class="mb-0">Open Gate: {{ \Carbon\Carbon::parse($events->jam_mulai)->format('H:i') }} WIB</p>
                            </div>

                            <div class="mt-2 d-flex gap-2">
                                <i class="fa-solid fa-location-dot fa-fw fs-5"></i>
                                <p class="mb-0">{{ $events->lokasi }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <img src="{{ asset('storage/' . $events->logo) }}" style="width: 40px; height: 40px">
                            <div>
                                <p class="mb-0">Diselenggarakan oleh</p>
                                <p class="mb-0 fw-bold">{{ $events->penyelenggara }}</p>
                            </div>
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
                            <p class="fw-bold">{{ $user }}</p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">Kode Invoice</p>
                            <p class="fw-bold">{{ $invoices->kode }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-0">Tanggal Pembelian</p>
                            <p class="fw-bold">{{ $purchase->waktu_pembelian }} WIB</p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">Tiket Dibeli</p>
                            @foreach($purchaseDetail as $detail)
                                <p class="fw-bold">{{ $detail->nama }} {{ $detail->pivot->jumlah }} tiket</p>
                            @endforeach
                        </div>
                    </div>
                    @if(!empty($concerts->link_ebooklet))
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-0">Link e-booklet</p>
                                <a class="fw-bold">{{ $concerts->link_ebooklet }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="d-flex align-items-center justify-content-between pe-0 ps-0">
                @foreach($tickets as $ticket)
                    <div class="card shadow col-6 col-md-4 col-lg-3">
                        <div class="card-body text-center">
                            <p class="mb-0 fw-bold">{{ $ticket->nama }} #{{ $ticket->number }}</p>
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
            <p>{!! nl2br(e($concerts->syarat_ketentuan)) !!}</p>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function printSection(sectionId) {
        var printContent = document.getElementById(sectionId).innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        location.reload(); // Refresh to restore functionality after printing
    }
</script>
@endsection