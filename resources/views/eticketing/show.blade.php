@extends('layouts.eticket')

@section('content')
<div class="container">
    <a href="{{ $backUrl ?? route('eticket.index') }}" class="btn btn-outline-primary">Kembali</a>

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
                    <p class="mt-3">Harga mulai dari <span class="fw-bold">Rp{{ number_format($hargaMulai, 0, ',', '.') }}</span></p>
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
                    <h5 class="fw-bold">Deskripsi</h5>
                    <p class="mt-1">{!! nl2br(e($concert->deskripsi)) !!}</p>
                    <h5 class="fw-bold">Seating Plan</h5>
                    <img src="{{ $concert->seating_plan }}" class="mt-1 mb-3 w-100">
                    <h5 class="fw-bold">Syarat dan Ketentuan</h5>
                    <p class="mt-1">{!! nl2br(e($concert->syarat_ketentuan)) !!}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 mt-3 mt-lg-0">
            <div class="card shadow position-sticky" style="top: 8rem;">
                <div class="card-body">
                    @foreach($tickets as $ticket)
                    <div class="mb-2 ticket-item" data-id="{{ $ticket->id }}" data-price="{{ $ticket->harga }}">
                        <h5>{{ $ticket->nama }}</h5>
                        <div class="d-flex align-items-center">
                            <p class="text-primary fs-6">Berakhir pada {{ \Carbon\Carbon::parse($ticket->pembelian_terakhir)->translatedFormat('d F Y H:i') }} WIB</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <button class="btn circle-btn btn-minus me-2">-</button>
                                <span class="fs-6 ticket-quantity">0</span>
                                <button class="btn circle-btn btn-plus ms-2">+</button>
                            </div>
                            <span class="fw-bold fs-6">Rp{{ number_format($ticket->harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between align-items-center my-3">
                        <h6>Total <span id="total-tickets">0</span> tiket</h6>
                        <h6 class="fw-bold">Rp<span id="total-price">0</span></h6>
                    </div>
                    <form action="{{ route('eticket.order', ['id' => $concert->id]) }}" id="form-beli" method="POST">
                        @csrf
                        <input type="hidden" name="tickets" id="selected-tickets">
                        <button type="submit" onclick="event.preventDefault(); beliTiket();" class="btn btn-primary w-100 fs-6 fw-bold">Beli Tiket</button>
                    </form>
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

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        //Total Pembelian
        const totalTicketsElement = document.getElementById("total-tickets");
        const totalPriceElement = document.getElementById("total-price");
        const MAX_TOTAL_TICKETS = 5; // Maximum total tickets allowed

        function updateTotal() {
            let totalTickets = 0;
            let totalPrice = 0;

            document.querySelectorAll(".ticket-item").forEach(ticketItem => {
                let quantity = parseInt(ticketItem.querySelector(".ticket-quantity").textContent);
                let price = parseInt(ticketItem.dataset.price);

                totalTickets += quantity;
                totalPrice += quantity * price;
            });

            totalTicketsElement.textContent = totalTickets;
            totalPriceElement.textContent = totalPrice.toLocaleString("id-ID");

            return totalTickets; // Return total tickets for validation
        }

        //Kurangin tiket
        document.querySelectorAll(".btn-minus").forEach(button => {
            button.addEventListener("click", function() {
                let ticketItem = this.closest(".ticket-item");
                if (!ticketItem) return;

                let quantityElement = ticketItem.querySelector(".ticket-quantity");
                let quantity = parseInt(quantityElement.textContent);

                if (quantity > 0) {
                    quantityElement.textContent = quantity - 1;
                }

                updateTotal();
            });
        });

        //Tambah Tiket
        document.querySelectorAll(".btn-plus").forEach(button => {
            button.addEventListener("click", function() {
                let ticketItem = this.closest(".ticket-item");
                if (!ticketItem) return;

                let quantityElement = ticketItem.querySelector(".ticket-quantity");
                let quantity = parseInt(quantityElement.textContent);

                if (updateTotal() < MAX_TOTAL_TICKETS) {
                    quantityElement.textContent = quantity + 1;
                } else {
                    alert("Total pembelian tiket maksimal adalah 5 per transaksi.");
                }

                updateTotal();
            });
        });

        //Beli Tiket
        function beliTiket() {
            let selectedTickets = [];
            document.querySelectorAll(".ticket-item").forEach(item => {
                let quantity = parseInt(item.querySelector(".ticket-quantity").textContent);
                if (quantity > 0) {
                    selectedTickets.push({
                        id: item.dataset.id, // Add ticket ID for reference
                        nama: item.querySelector("h5").textContent.trim(),
                        harga: item.dataset.price,
                        jumlah: quantity
                    });
                }
            });
            // Store selected tickets in hidden input
            document.getElementById("selected-tickets").value = JSON.stringify(selectedTickets);
            // Submit the form
            document.getElementById("form-beli").submit();
        }
        window.beliTiket = beliTiket;
    });
</script>
@endsection