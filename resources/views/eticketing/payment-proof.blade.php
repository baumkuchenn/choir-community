@extends('layouts.eticket')

@section('content')
<div class="container">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow">
                <div class="card-body">
                    <div class="text-center m-auto">
                        <i class="fa-solid fa-circle-check fs-1 mb-1"></i>
                        <h4 class="fw-bold">Pembayaran Berhasil</h4>
                        <p>Tanggal pembayaran {{ \Carbon\Carbon::parse($purchases->waktu_pembayaran)->translatedFormat('j F Y H:i') }} WIB</p>
                    </div>
                    <h4 class="fw-bold">Ringkasan Pembelian</h4>
                    @php
                        $totalTiket = 0;
                        $subtotal = 0;
                    @endphp
                    @foreach ($tiketDibeli as $tiket)
                        @php
                            $subtotal += $tiket['jumlah'] * $tiket['harga'];
                            $totalTiket += $tiket['jumlah'];
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
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <p class="mb-0">Total <span class="fw-light">({{ $totalTiket }} tiket)</span></p>
                        <p class="mb-0">Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between gap-2 h-auto">
                        <p class="mb-0">Potongan</p>
                        <p class="mb-0">Rp{{ number_format($subtotal - $purchases->total_tagihan, 0, ',', '.') }}</p>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <p>Total Pembayaran</p>
                        <p class="fw-bold">Rp{{ number_format($purchases->total_tagihan, 0, ',', '.') }}</p>
                    </div>

                    <h4 class="fw-bold">Bukti Pembayaran</h4>
                    <img src="{{ asset('storage/' . $purchases->gambar_pembayaran) }}" alt="bukti-pembayaran" class="w-75 d-block mx-auto my-3">

                    @if(!empty($purchases->invoice))
                        <h4 class="fw-bold">Pembayaran Berhasil</h4>
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <p class="mb-0">Kode Invoice</p>
                                <p class="fw-bold">{{ $purchases->invoice->kode }}</p>
                            </div>
                            <form action="{{ route('eticket.invoice', ['id' => $purchases->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <button class="btn btn-primary">Lihat Invoice</button>
                            </form>
                        </div>
                    @endif

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
    <div class="row mt-3 w-100 justify-content-center">
        <div class="col-6 col-lg-5">
            <a href="{{ route('eticket.myticket') }}" class="btn btn-outline-primary w-100 fw-bold">Cek Status Pembayaran</a>
        </div>
        <div class="col-6 col-lg-5">
            <a href="{{ route('eticket.index') }}" class="btn btn-primary w-100 fw-bold">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {

    });
</script>
@endsection