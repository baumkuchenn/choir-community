@extends('layouts.eticket')

@section('content')
<form action="{{ route('eticket.payment', ['id' => $purchase->id]) }}" method="POST" id="form-payment" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="payment-menu" value="payment">
    <div class="container">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow">
                    <div class="card-body bg-primary-subtle text-center">
                        <p class="mb-0">Sisa waktu untuk membayar pesanan <span id="timer"></span></p>
                    </div>
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

                        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                            <h4 class="mb-0 fw-bold">Transfer Bank {{ $concert->nama_singkatan }}</h4>
                            <img src="{{ $concert->logo }}" style="width: 25px; height: 25px;">
                        </div>

                        <p class="mb-0">Nomor Rekening</p>
                        <p class="fw-bold mb-1">{{ $concert->no_rekening }}</p>

                        <p class="mb-0">Nama Pemilik Rekening</p>
                        <p class="fw-bold mb-1">{{ $concert->pemilik_rekening }}</p>

                        <p class="mb-0">Berita Transfer</p>
                        <p class="fw-bold">{{ $concert->berita_transfer }}</p>

                        <div class="card shadow bg-body-secondary border-0 mb-3">
                            <div class="card-body">
                                <p class="mb-0">Setelah melakukan pembayaran, harap upload bukti pembayaran ke kolom dibawah untuk tahap verifikasi pembayaran.</p>
                            </div>
                        </div>

                        <h4 class="fw-bold">Upload Bukti Pembayaran</h4>
                        <div class="file-drop-area" id="dropArea">
                            <i class="bi bi-cloud-upload file-drop-icon"></i>
                            <p class="mt-2">Seret & taruh bukti pembayaranmu disini atau <label for="fileInput" class="text-primary fw-bold" style="cursor: pointer;">cari</label></p>
                            <input type="file" name="bukti_pembayaran" id="fileInput" class="d-none">
                            <p id="fileName" class="mt-2 text-muted">Belum ada file yang dipilih. Ukuran maksimal 2 MB.</p>
                        </div>

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
            <div class="col-12 col-lg-10">
                <button type="submit" class="btn btn-primary w-100">Simpan Pembayaran</button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("form-payment").addEventListener("submit", function(event) {
            let fileInput = document.getElementById("fileInput");
            if (!fileInput.files.length) {
                event.preventDefault(); // Mencegah submit jika file belum dipilih
                alert("Harap upload file bukti pembayaran.");
            }
        });
        //Timer
        let waktuPembelian = new Date("{{ $purchase->waktu_pembelian }}").getTime();
        let expiryTime = waktuPembelian + (24 * 60 * 60 * 1000); // Add 24 hours

        function updateTimer() {
            let now = new Date().getTime();
            let remaining = expiryTime - now;

            if (remaining <= 0) {
                document.getElementById("countdown").innerHTML = "<strong>Pembayaran telah kadaluarsa!</strong>";
                return;
            }

            let hours = Math.floor((remaining / (1000 * 60 * 60)) % 24);
            let minutes = Math.floor((remaining / (1000 * 60)) % 60);
            let seconds = Math.floor((remaining / 1000) % 60);

            document.getElementById("timer").innerText = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateTimer, 1000);
        updateTimer();

        //Upload File
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileInput');
        const fileNameDisplay = document.getElementById('fileName');

        // Click to browse
        dropArea.addEventListener('click', () => fileInput.click());

        // Handle file selection
        fileInput.addEventListener('change', (event) => {
            updateFileDisplay(event.target.files[0]);
        });

        // Drag over effect
        dropArea.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropArea.classList.add('dragover');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('dragover');
        });

        // Drop file
        dropArea.addEventListener('drop', (event) => {
            event.preventDefault();
            dropArea.classList.remove('dragover');
            const file = event.dataTransfer.files[0];
            fileInput.files = event.dataTransfer.files; // Update input
            updateFileDisplay(file);
        });

        function updateFileDisplay(file) {
            if (file) {
                fileNameDisplay.textContent = file.name;
            } else {
                fileNameDisplay.textContent = "Belum ada file yang dipilih";
            }
        }
    });
</script>
@endsection