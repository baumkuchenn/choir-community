@extends('layouts.eticket')

@section('content')
<div class="ps-3 pe-3 d-flex flex-nowrap">
    <div class="d-none d-lg-flex flex-column flex-shrink-0 p-3 border-end" style="width: 20%;">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('eticket.myticket') }}" class="nav-link active">
                    <i class="fas fa-ticket fa-fw me-2"></i>
                    Tiket Saya
                </a>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}" class="nav-link text-body">
                    <i class="fas fa-user fa-fw me-2"></i>
                    Profil
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-body">
                    <i class="fas fa-cog fa-fw me-2"></i>
                    Pengaturan
                </a>
            </li>
        </ul>
    </div>
    <div class="ps-3" style="width: 90%;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="col-md-11 col-lg-11 mx-auto">
            <h4 class="mb-3 fw-bold">Tiket Saya</h4>
            <h5 class="mb-3 fw-bold">Konser Berlangsung</h5>
            <hr>
            @if ($purchaseBerlangsung->isEmpty())
                <p class="text-center">Kamu belum memiliki tiket, silahkan membeli tiket terlebih dahulu</p>
            @else
                @foreach ($purchaseBerlangsung as $purchase)
                    <div class="card shadow border-0 mb-3">
                        <div class="card-body">
                            <p>
                                Pembelian {{ \Carbon\Carbon::parse($purchase->waktu_pembelian)->translatedFormat('j F Y') }}
                                @if ($purchase->status == 'verifikasi')
                                    <span class="bg-info fw-bold p-1">Verifikasi Pembayaran</span>
                                @elseif ($purchase->status == 'selesai')
                                    <span class="bg-success text-white fw-bold p-1">Pembayaran Berhasil</span>
                                @elseif ($purchase->status == 'batal')
                                    <span class="bg-danger text-white fw-bold p-1">Pesanan Dibatalkan</span>
                                @endif
                            </p>
                            <hr>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-md-7">
                                        <h5 class="fw-bold">{{ $purchase->concert->event->nama }}</h5>
                                        <div class="mt-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-calendar-days fa-fw fs-5"></i>
                                                <p class="mb-0">{{ \Carbon\Carbon::parse($purchase->concert->event->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                                            </div>
                                            <div class="mt-2 d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-clock fa-fw fs-5"></i>
                                                <p class="mb-0">Open Gate: {{ \Carbon\Carbon::parse($purchase->concert->event->jam_mulai)->format('H:i') }} WIB</p>
                                            </div>
                                            <div class="mt-2 d-flex gap-2">
                                                <i class="fa-solid fa-location-dot fa-fw fs-5"></i>
                                                <p class="mb-0">{{ $purchase->concert->event->lokasi }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-end d-none d-md-block">
                                        <p class="mb-1">Total Tagihan</p>
                                        <p class="fw-bold fs-5">Rp{{ number_format($purchase->total_tagihan, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-md-5 d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/' . $purchase->logo) }}" style="width: 40px; height: 40px">
                                        <div>
                                            <p class="mb-0">Diselenggarakan oleh</p>
                                            <p class="mb-0 fw-bold">{{ $purchase->penyelenggara }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-7 d-none d-md-flex justify-content-end gap-2">
                                        <form action="{{ route('eticket.payment', ['id' => $purchase->id]) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="payment-menu" value="check-payment">
                                            <button class="btn btn-outline-primary">Lihat Detail Transaksi</button>
                                        </form>
                                        @if($purchase->status == 'selesai' && $purchase->check_in == 'tidak')
                                            <form action="{{ route('eticket.ticket', ['id' => $purchase->id]) }}" target="_blank" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <button class="btn btn-primary">Lihat Tiket</button>
                                            </form>
                                        @endif
                                        @if($purchase->check_in == 'ya' && $purchase->feedbacks == 'belum')
                                            <!-- Pengecekan udah isi feedback atau belum -->
                                            <form action="{{ route('eticket.feedback', ['id' => $purchase->id]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="feedback-menu" value="check-feedback">
                                                <button class="btn btn-primary">Beri Feedback</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-md-5 text-start d-block d-md-none">
                                        <p class="mb-1">Total Tagihan</p>
                                        <p class="fw-bold fs-5">Rp{{ number_format($purchase->total_tagihan, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="col-md-7 d-flex d-md-none justify-content-end gap-2">
                                        <form action="{{ route('eticket.payment', ['id' => $purchase->id]) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="payment-menu" value="check-payment">
                                            <button class="btn btn-outline-primary">Lihat Detail Transaksi</button>
                                        </form>
                                        @if($purchase->status == 'selesai' && $purchase->check_in == 'tidak')
                                            <form action="{{ route('eticket.ticket', ['id' => $purchase->id]) }}" target="_blank" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <button class="btn btn-primary">Lihat Tiket</button>
                                            </form>
                                        @endif
                                        @if($purchase->check_in == 'ya' && $purchase->feedbacks == 'belum')
                                            <form action="{{ route('eticket.feedback', ['id' => $konser->id]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="feedback-menu" value="check-feedback">
                                                <button class="btn btn-primary">Beri Feedback</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <h5 class="mt-4 mb-3 fw-bold">Konser Lalu</h5>
            <hr>
            @if ($purchaseLalu->isEmpty())
                <p class="text-center">Kamu belum memiliki tiket, silahkan membeli tiket terlebih dahulu</p>
            @else
                @foreach ($purchaseLalu as $purchase)
                    <div class="card shadow border-0 mb-3">
                        <div class="card-body">
                            <p>
                                Pembelian {{ \Carbon\Carbon::parse($purchase->waktu_pembelian)->translatedFormat('j F Y') }}
                                @if ($purchase->status == 'verifikasi')
                                    <span class="bg-info fw-bold p-1">Verifikasi Pembayaran</span>
                                @elseif ($purchase->status == 'selesai')
                                    <span class="bg-success text-white fw-bold p-1">Pembayaran Berhasil</span>
                                @elseif ($purchase->status == 'batal')
                                    <span class="bg-danger text-white fw-bold p-1">Pesanan Dibatalkan</span>
                                @endif
                            </p>
                            <hr>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-md-7">
                                        <h5 class="fw-bold">{{ $purchase->concert->event->nama }}</h5>
                                        <div class="mt-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-calendar-days fa-fw fs-5"></i>
                                                <p class="mb-0">{{ \Carbon\Carbon::parse($purchase->concert->event->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                                            </div>
                                            <div class="mt-2 d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-clock fa-fw fs-5"></i>
                                                <p class="mb-0">Open Gate: {{ \Carbon\Carbon::parse($purchase->concert->event->jam_mulai)->format('H:i') }} WIB</p>
                                            </div>
                                            <div class="mt-2 d-flex gap-2">
                                                <i class="fa-solid fa-location-dot fa-fw fs-5"></i>
                                                <p class="mb-0">{{ $purchase->concert->event->lokasi }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-end d-none d-md-block">
                                        <p class="mb-1">Total Tagihan</p>
                                        <p class="fw-bold fs-5">Rp{{ number_format($purchase->total_tagihan, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-md-5 d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/' . $purchase->logo) }}" style="width: 40px; height: 40px">
                                        <div>
                                            <p class="mb-0">Diselenggarakan oleh</p>
                                            <p class="mb-0 fw-bold">{{ $purchase->penyelenggara }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-7 d-none d-md-flex justify-content-end gap-2">
                                        <form action="{{ route('eticket.payment', ['id' => $purchase->id]) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="payment-menu" value="check-payment">
                                            <button class="btn btn-outline-primary">Lihat Detail Transaksi</button>
                                        </form>
                                        @if($purchase->status == 'selesai' && $purchase->check_in == 'tidak')
                                            <form action="{{ route('eticket.ticket', ['id' => $purchase->id]) }}" target="_blank" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <button class="btn btn-primary">Lihat Tiket</button>
                                            </form>
                                        @endif
                                        @if($purchase->check_in == 'ya' && $purchase->feedbacks == 'belum')
                                            <form action="{{ route('eticket.feedback', ['id' => $purchase->id]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="feedback-menu" value="check-feedback">
                                                <button class="btn btn-primary">Beri Feedback</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-md-5 text-start d-block d-md-none">
                                        <p class="mb-1">Total Tagihan</p>
                                        <p class="fw-bold fs-5">Rp{{ number_format($purchase->total_tagihan, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="col-md-7 d-flex d-md-none justify-content-end gap-2">
                                        <form action="{{ route('eticket.payment', ['id' => $purchase->id]) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="payment-menu" value="check-payment">
                                            <button class="btn btn-outline-primary">Lihat Detail Transaksi</button>
                                        </form>
                                        @if($purchase->status == 'selesai' && $purchase->check_in == 'tidak')
                                            <form action="{{ route('eticket.ticket', ['id' => $purchase->id]) }}" target="_blank" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <button class="btn btn-primary">Lihat Tiket</button>
                                            </form>
                                        @endif
                                        @if($purchase->check_in == 'ya' && $purchase->feedbacks == 'belum')
                                            <form action="{{ route('eticket.feedback', ['id' => $purchase->id]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="feedback-menu" value="check-feedback">
                                                <button class="btn btn-primary">Beri Feedback</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
</div>
@endsection