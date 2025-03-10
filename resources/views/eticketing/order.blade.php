@extends('layouts.eticket')

@section('content')
<div class="container">
    <form action="{{ route('eticket.purchase', ['id' => $concert->id]) }}" id="form-purchase" method="POST">
        @csrf
        @foreach ($tiketDipilih as $tiket)
        <input type="hidden" name="tickets[]" value="{{json_encode($tiket)}}">
        @endforeach
        <input type="hidden" name="purchase-menu" value="purchase">
    </form>
    <h3 class="fw-bold">Informasi Tiket yang Dibeli</h3>
    <div class="row mt-3">
        <div class="col-12 col-lg-8">
            <div class="row">
                <img src="{{ $concert->gambar }}" style="width: 100%; max-height: 360px;">
            </div>
            <div class="row mt-3">
                <div class="card border-0">
                    <div class="card-body shadow border rounded">
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
        <div class="col-12 col-lg-4">
            <div class="card shadow position-sticky" style="top: 8rem;">
                <div class="card-body">
                    <h4 class="fw-bold">Ringkasan Pembelian</h4>
                    @php
                    $totalTiket = 0;
                    $totalHarga = 0;
                    @endphp
                    @foreach ($tiketDipilih as $tiket)
                    @php
                    $subtotal = $tiket['jumlah'] * $tiket['harga'];
                    $totalTiket += $tiket['jumlah'];
                    $totalHarga += $subtotal;
                    @endphp
                    <div class="mt-2 d-flex align-items-center gap-3">
                        <i class="fa-solid fa-ticket fa-fw fs-3"></i>
                        <div>
                            <p class="mb-0">{{ $tiket['nama'] }}</p>
                            <p class="mb-0 fw-light">{{ $tiket['jumlah'] }} tiket x Rp{{ number_format($tiket['harga'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                    <a href="" class="btn btn-outline-primary w-100">Pakai Kode Promo/Referal</a>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <p class="mb-0">Total <span class="fw-light">({{ $totalTiket }} tiket)</span></p>
                        <p class="mb-0">Rp{{ number_format($totalHarga, 0, ',', '.') }}</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between gap-2 h-auto">
                        <p class="mb-0">Potongan</p>
                        <p class="mb-0">Rp{{ number_format('0', 0, ',', '.') }}</p>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <p>Total Pembayaran</p>
                        <p class="fw-bold">Rp{{ number_format($totalHarga, 0, ',', '.') }}</p>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fs-6 fw-bold" onclick="event.preventDefault(); document.getElementById('form-purchase').submit();">Bayar Tiket</button>
                    @if(session('error'))
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            alert("{{ session('error') }}");
                        });
                    </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection