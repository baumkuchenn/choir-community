@extends('layouts.eticket')

@section('content')
<div class="container">
    <form action="{{ route('eticket.purchase', ['id' => $event->concert->id]) }}" id="form-purchase" method="POST">
        @csrf
        @foreach ($tiketDipilih as $tiket)
            <input type="hidden" name="tickets[]" value="{{json_encode($tiket)}}">
        @endforeach
        <input type="hidden" name="purchase-menu" value="purchase">
        <input type="hidden" name="kupons_id" id="input-coupon-code">
        <input type="hidden" name="referals_id" id="input-referral-code">
        <input type="hidden" name="discount_amount" id="input-discount-amount" value="0">
    </form>
    <h3 class="fw-bold">Informasi Tiket yang Dibeli</h3>
    <div class="row mt-3">
        <div class="col-12 col-lg-8">
            <div class="row">
                <img src="{{ asset('storage/'. $event->concert->gambar) }}" style="width: 100%; aspect-ratio: 16/9; object-fit: cover;">
            </div>
            <div class="row mt-3">
                <div class="card border-0">
                    <div class="card-body shadow border rounded">
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
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <img src="{{ asset('storage/'. $event->logo) }}" style="width: 40px; height: 40px">
                            <div>
                                <p class="mb-0">Diselenggarakan oleh</p>
                                <p class="mb-0 fw-bold">{{ $event->penyelenggara }}</p>
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
                    <button type="button" class="btn btn-outline-primary w-100 create-modal" data-action="{{ route('eticket.kupon.use', $event->id) }}">Pakai Kode Promo/Referal</button>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <p class="mb-0">Total <span class="fw-light">({{ $totalTiket }} tiket)</span></p>
                        <p class="mb-0">Rp{{ number_format($totalHarga, 0, ',', '.') }}</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between gap-2 h-auto">
                        <p class="mb-0">Potongan</p>
                        <p id="potongan-label" class="mb-0">Rp{{ number_format('0', 0, ',', '.') }}</p>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <p>Total Pembayaran</p>
                        <p id="total-label" class="fw-bold">Rp{{ number_format($totalHarga, 0, ',', '.') }}</p>
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
<div id="modalContainer"></div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".create-modal").forEach((button) => {
            button.addEventListener("click", function() {
                let route = this.dataset.action;

                // Clean up any old modals & backdrops first
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                
                fetch(route)
                    .then(response => response.text())
                    .then(html => {
                        let modalContainer = document.getElementById("modalContainer");
                        modalContainer.innerHTML = '';
                        modalContainer.innerHTML = html;

                        let createModal = document.getElementById('addKuponModal');
                        let modalInstance = bootstrap.Modal.getInstance(createModal)
                            || new bootstrap.Modal(createModal);
                        modalInstance.show();

                        document.getElementById('applyCoupon').addEventListener('click', () => {
                            const selectedOption = document.getElementById('couponSelect').selectedOptions[0];
                            const couponCode = selectedOption.value;
                            const discountAmount = parseInt(selectedOption.dataset.discount);
                            const referralCode = document.getElementById('referralInput').value;

                            // Update hidden form values
                            document.getElementById('input-coupon-code').value = couponCode;
                            document.getElementById('input-referral-code').value = referralCode;
                            document.getElementById('input-discount-amount').value = discountAmount;

                            // Optional: update displayed total in UI (no page reload)
                            const totalPrice = {{ $totalHarga }};
                            const newTotal = totalPrice - discountAmount;
                            document.getElementById('potongan-label').textContent = `Rp${discountAmount.toLocaleString('id-ID')}`;
                            document.getElementById('total-label').textContent = `Rp${newTotal.toLocaleString('id-ID')}`;

                            modalInstance.hide();
                        });
                    });
            });
        });
    });
</script>
@endsection