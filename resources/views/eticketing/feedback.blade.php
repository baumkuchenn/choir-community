@extends('layouts.eticket')

@section('content')
<div class="container">
    <a href="{{ url()->previous() }}" class="btn btn-outline-primary">Kembali</a>
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
                        @if(!empty($concert->link_ebooklet))
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-bookmark fa-fw fs-5"></i>
                                <div>
                                    <p class="mb-0">Link e-booklet :</p>
                                    <a class="fw-bold">{{ $concert->link_ebooklet }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <hr>
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <img src="{{ asset('storage/' . $event->logo) }}" style="width: 40px; height: 40px">
                        <div>
                            <p class="mb-0">Diselenggarakan oleh</p>
                            <p class="mb-0 fw-bold">{{ $event->penyelenggara }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('eticket.save-feedback', ['id' => $purchase->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="feedback-menu" value="save-feedback">
        @if($concert->donasi === 'ya')
            <input type="hidden" name="donasi" value="ya">
            <div class="row mt-3 p-2">
                <h4 class="fw-bold p-0">Kirim donasi untuk membantu kegiatan ini</h4>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <h4 class="mb-0 fw-bold">Transfer Bank {{ $concert->bank->nama_singkatan }}</h4>
                                    <img src="{{ asset('storage/' . $concert->bank->logo) }}" style="width: 45px; object-fit: cover;">
                                </div>

                                <p class="mb-0">Nomor Rekening</p>
                                <p class="fw-bold mb-1">{{ $concert->no_rekening }}</p>

                                <p class="mb-0">Nama Pemilik Rekening</p>
                                <p class="fw-bold">{{ $concert->pemilik_rekening }}</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <p class="fw-light mb-0">Isi kotak berikut jika ingin melakukan donasi</p>
                                <div>
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="" value="{{ old('nama') }}">
                                    @error('nama')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mt-1">
                                    <label for="jumlah" class="form-label">Nominal</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="" value="{{ old('jumlah') }}">
                                    </div>
                                    @error('jumlah')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row mt-3 p-2">
            <h4 class="fw-bold p-0">Bagaimana pendapatmu terkait konser ini?</h4>
            <textarea name="feedback" class="form-control" placeholder="Ceritakan pengalamanmu pada konser ini" rows="5" maxlength="1000" id="feedback-area" required>{{ old('feedback') }}</textarea>
            <small class="text-muted d-block mt-1" id="feedback-counter">0/1000 karakter</small>
            <div class="file-drop-area mt-3" id="dropArea">
                <i class="bi bi-cloud-upload file-drop-icon"></i>
                <p class="mt-2">Kasih foto pengalamanmu pada konser ini</p>
                <input type="file" name="gambar-feedback" id="fileInput" class="d-none">
                <p id="fileName" class="mt-2 text-muted">Belum ada file yang dipilih. Maksimal ukuran file 2 MB.</p>
            </div>
        </div>
        <div class="row mt-2 p-2">
            <button type="submit" class="btn btn-primary w-100 fw-bold">Kirim ke Penyelenggara Konser</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        //Counter text area
        const feedbackInput = document.getElementById("feedback-area");
        const charCount = document.getElementById("feedback-counter");

        feedbackInput.addEventListener("input", function() {
            charCount.textContent = `${feedbackInput.value.length}/1000 karakter`;
        });


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