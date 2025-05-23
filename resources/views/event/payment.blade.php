@extends('layouts.management')

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
                    @if($purchases->gambar_pembayaran)
                        <img src="{{ asset('storage/' . $purchases->gambar_pembayaran) }}" alt="bukti-pembayaran" class="w-75 d-block mx-auto my-3">
                    @else
                        <p>Bukti pembayaran belum diupload.</p>
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
        @if($purchases->status == 'verifikasi')
            <div class="col-6 col-lg-5">
                <form action="{{ route('events.verification', $purchases->id) }}" method="POST" enctype="multipart/form-data" class="mb-0">
                    @csrf
                    <input type="hidden" name="status_verifikasi" value="batal">
                    <button class="btn btn-outline-danger w-100 fw-bold">Batalkan Pembayaran</button>
                </form>
            </div>
            <div class="col-6 col-lg-5">
                <form action="{{ route('events.verification', $purchases->id) }}" method="POST" enctype="multipart/form-data" class="mb-0">
                    @csrf
                    <input type="hidden" name="status_verifikasi" value="terima">
                    <button class="btn btn-primary w-100 fw-bold">Konfirmasi Pembayaran</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {

    });
</script>
@endsection