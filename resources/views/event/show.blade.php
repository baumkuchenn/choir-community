@extends('layouts.management')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="col-md-11 col-lg-11 mx-auto">
        <h2 class="mb-3 fw-bold text-center">Detail Kegiatan Komunitas</h2>
        <a href="{{ $backUrl ?? route('events.index') }}" class="btn btn-outline-primary">Kembali</a>
        <nav class="navbar">
            <div class="d-flex flex-nowrap overflow-auto w-100 hide-scrollbar border-bottom border-black" id="navbarNav">
                <div class="navbar-nav fs-5 flex-row" role="tablist">
                    <a class="nav-link active px-3" data-bs-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="true">Detail</a>
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#penyanyi" role="tab" aria-controls="penyanyi" aria-selected="false">Penyanyi</a>
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#panitia" role="tab" aria-controls="panitia" aria-selected="false">Panitia</a>
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#tiket" role="tab" aria-controls="tiket" aria-selected="false">Tiket</a>
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#feedback" role="tab" aria-controls="feedback" aria-selected="false">Feedback</a>
                </div>
            </div>
        </nav>

        <style>
            /* Hide scrollbar but allow scrolling */
            .hide-scrollbar {
                -ms-overflow-style: none;
                /* Hide scrollbar for IE and Edge */
                scrollbar-width: none;
                /* Hide scrollbar for Firefox */
            }

            .hide-scrollbar::-webkit-scrollbar {
                display: none;
                /* Hide scrollbar for Chrome, Safari, and Opera */
            }
        </style>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="detail" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <label for="nama" class="form-label">Nama Kegiatan</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="" value="{{ old('nama', $event->nama) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan</label>
                        <select class="form-control" id="jenis_kegiatan" name="jenis_kegiatan" required>
                            <option value="" disabled selected>Pilih jenis kegiatan</option>
                            <option value="INTERNAL" {{ old('jenis_kegiatan', $event->jenis_kegiatan) == 'INTERNAL' ? 'selected' : '' }}>Internal</option>
                            <option value="EKSTERNAL" {{ old('jenis_kegiatan', $event->jenis_kegiatan) == 'EKSTERNAL' ? 'selected' : '' }}>External</option>
                            <option value="KONSER" {{ old('jenis_kegiatan', $event->jenis_kegiatan) == 'KONSER' ? 'selected' : '' }}>Konser</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label for="sub_kegiatan" class="form-label">Sub Kegiatan</label>
                        <select class="form-control" id="sub_kegiatan" name="sub_kegiatan" required>
                            <option value="" disabled selected>Pilih sub kegiatan</option>
                            @foreach ($events as $subEvent)
                            <option value="{{ $subEvent->id }}" {{ old('sub_kegiatan', $event->sub_kegiatan_id) == $subEvent->id ? 'selected' : '' }}>
                                {{ $subEvent->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" placeholder="" value="{{ old('tanggal_mulai', $event->tanggal_mulai) }}" required>
                    </div>
                    <div class="col-6">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" placeholder="" value="{{ old('tanggal_selesai', $event->tanggal_selesai) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" placeholder="" value="{{ old('jam_mulai', $event->jam_mulai) }}" required>
                    </div>
                    <div class="col-6">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" placeholder="" value="{{ old('jam_selesai', $event->jam_selesai) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <label for="tanggal_gladi" class="form-label">Tanggal Gladi</label>
                        <input type="date" class="form-control" id="tanggal_gladi" name="tanggal_gladi" placeholder="" value="{{ old('tanggal_gladi', $event->tanggal_gladi) }}">
                    </div>
                    <div class="col-6">
                        <label for="jam_gladi" class="form-label">Jam Gladi</label>
                        <input type="time" class="form-control" id="jam_gladi" name="jam_gladi" placeholder="" value="{{ old('jam_gladi', $event->jam_gladi) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="" value="{{ old('lokasi', $event->lokasi) }}" required>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="penyanyi" role="tabpanel">
                <h5>Daftar Penyanyi</h5>
                <p>Isi daftar penyanyi di sini...</p>
            </div>
            <div class="tab-pane fade" id="panitia" role="tabpanel">
                <h5>Daftar Panitia</h5>
                <p>Isi daftar panitia di sini...</p>
            </div>
            <div class="tab-pane fade" id="tiket" role="tabpanel">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Daftar Pembeli Tiket</h5>
                        <div> {!! $purchases->links() !!} </div>
                    </div>
                    <table class="table table-bordered shadow text-center">
                        <thead>
                            <tr class="bg-primary">
                                <th>Nama</th>
                                <th>Nomor Handphone</th>
                                <th>Waktu Pembelian</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($purchases->isNotEmpty())
                                @foreach($purchases as $purchase)
                                    <tr>
                                        @php
                                            $invoice = $purchase->invoice;
                                            $tickets = $invoice ? $invoice->tickets : collect();
                                            $checkedInCount = $tickets->where('check_in', 'YA')->count();
                                            $totalTickets = $tickets->count();
                                        @endphp
                                        <td>{{ $purchase->user->name }}</td>
                                        <td>{{ $purchase->user->no_handphone }}</td>
                                        <td>{{ \Carbon\Carbon::parse($purchase->waktu_pembayaran)->format('d-m-Y H:i') }}</td>
                                        <td>
                                            @if($totalTickets > 0)
                                                @if($checkedInCount > 0)
                                                    Tiket Checked In {{ $checkedInCount }}/{{ $totalTickets }}
                                                @else
                                                    E-ticket Terkirim ({{ $totalTickets }} tiket)
                                                @endif
                                            @elseif($purchase->status === 'VERIFIKASI')
                                                Verifikasi Pembayaran
                                            @endif
                                        </td>
                                        <td class="d-flex justify-content-center gap-3">
                                            @if($totalTickets > 0)
                                                @if($checkedInCount != $totalTickets)
                                                    <button class="btn btn-primary loadCheckInForm" data-purchase-id="{{ $purchase->id }}" data-concert-id="{{ $concert->id }}">Check In</button>
                                                @endif
                                            @elseif($purchase->status === 'VERIFIKASI')
                                                <form action="{{ route('events.payment', $purchase->id) }}" method="POST" enctype="multipart/form-data" class="mb-0">
                                                    @csrf
                                                    <button class="btn btn-primary">Lihat Detail</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Belum ada pembeli</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Jenis Tiket</h5>
                        <div> {!! $ticketTypes->links() !!} </div>
                    </div>
                    <table class="table table-bordered shadow text-center">
                        <thead>
                            <tr class="bg-primary">
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Terjual/Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($ticketTypes->isNotEmpty())
                                @foreach($ticketTypes as $type)
                                <tr>
                                    <td>{{ $type->nama }}</td>
                                    <td>Rp{{ number_format($type->harga, 0, ',', '.') }}</td>
                                    <td>{{ $type->terjual }}/{{ $type->jumlah }}</td>
                                    <td class="d-flex justify-content-center gap-3">
                                        <button class="btn btn-primary loadEditForm" data-id="{{ $type->id }}" data-concert-id="{{ $concert->id }}">Ubah</button>
                                        <button class="btn btn-outline-danger deleteBtn" data-id="{{ $type->id }}" data-name="tiket {{ $type->nama }}" data-action="{{ route('ticket-types.destroy', $type->id) }}">Hapus</button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">Belum ada jenis tiket yang dijual</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <button class="btn btn-primary fw-bold" id="loadCreateForm" data-concert-id="{{ $concert->id }}">+ Tambah Jenis</button>
                </div>

                <div id="modalContainer"></div>

                <div class="mb-3">
                    <h5>E-Booklet</h5>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <p class="mb-0">Menggunakan e-booklet?</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ebooklet" id="ebooklet_yes" value="YA"
                                    {{ old('ebooklet', $concert->ebooklet) == 'YA' ? 'checked' : '' }}>
                                <label class="form-check-label" for="ebooklet_yes">Ya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ebooklet" id="ebooklet_no" value="TIDAK"
                                    {{ old('ebooklet', $concert->ebooklet) == 'TIDAK' ? 'checked' : '' }}>
                                <label class="form-check-label" for="ebooklet_no">Tidak</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-8" id="ebooklet_link_container" style="display: none;">
                            <label for="link_ebooklet" class="form-label">Link e-booklet</label>
                            <input type="text" class="form-control" id="link_ebooklet" name="link_ebooklet" placeholder=""
                                value="{{ old('link_ebooklet', $concert->link_ebooklet) }}" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h5>Donasi</h5>
                    <div class="row">
                        <div class="col-12">
                            <p class="mb-0">Menerima donasi?</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="donasi" id="donasi_yes" value="YA"
                                    {{ old('donasi', $concert->donasi == 'YA') ? 'checked' : '' }}>
                                <label class="form-check-label" for="donasi_yes">Ya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="donasi" id="donasi_no" value="TIDAK"
                                    {{ old('donasi', $concert->donasi == 'TIDAK') ? 'checked' : '' }}>
                                <label class="form-check-label" for="donasi_no">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1" id="donasi_container" style="display: none;">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="mb-1 fw-medium">Daftar Donatur</p>
                                <div> {!! $donations->links() !!} </div>
                            </div>
                            <table class="table table-bordered shadow text-center">
                                <thead>
                                    <tr class="bg-primary">
                                        <th>Nama</th>
                                        <th>Nomor Handphone</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($donations->isNotEmpty())
                                        @foreach($donations as $donation)
                                        <tr>
                                            <td>{{ $donation->user->name }}</td>
                                            <td>{{ $donation->user->no_handphone }}</td>
                                            <td>Rp{{ number_format($donation->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3">Belum ada donatur</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h5>Kupon</h5>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <p class="mb-0">Menggunakan kupon?</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kupon" id="kupon_yes" value="YA"
                                    {{ old('kupon', $concert->kupon == 'YA') ? 'checked' : '' }}>
                                <label class="form-check-label" for="kupon_yes">Ya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kupon" id="kupon_no" value="TIDAK"
                                    {{ old('kupon', $concert->kupon == 'TIDAK') ? 'checked' : '' }}>
                                <label class="form-check-label" for="kupon_no">Tidak</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-8" id="tipe_kupon_container" style="display: none;">
                            <label for="tipe_kupon" class="form-label">Tipe kupon yang digunakan</label>
                            <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan</label>
                            <select class="form-control" id="tipe_kupon" name="tipe_kupon" required>
                                <option value="" disabled selected>Pilih tipe kupon</option>
                                <option value="KUPON" {{ old('tipe_kupon', $concert->tipe_kupon) == 'KUPON' ? 'selected' : '' }}>Kupon</option>
                                <option value="REFERAL" {{ old('tipe_kupon', $concert->tipe_kupon) == 'REFERAL' ? 'selected' : '' }}>Referal</option>
                                <option value="KEDUANYA" {{ old('tipe_kupon', $concert->tipe_kupon) == 'KEDUANYA' ? 'selected' : '' }}>Keduanya</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-1" id="kupon_container" style="display: none;">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="mb-1 fw-medium">Kode Kupon</p>
                                <div> {!! $donations->links() !!} </div>
                            </div>
                            <table class="table table-bordered shadow text-center">
                                <thead>
                                    <tr class="bg-primary">
                                        <th>Kode</th>
                                        <th>Nominal</th>
                                        <th>Terpakai/Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($donations->isNotEmpty())
                                        @foreach($donations as $donation)
                                        <tr>
                                            <td>{{ $donation->user->name }}</td>
                                            <td>{{ $donation->user->no_handphone }}</td>
                                            <td>{{ $donation->jumlah }}</td>
                                            <td class="d-flex justify-content-center gap-3">
                                                <a href="{{ route('events.edit', $concert->id) }}" class="btn btn-primary">Ubah</a>
                                                <form action="{{ route('events.destroy', $concert->id) }}" method="POST" enctype="multipart/form-data" class="mb-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-primary">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4">Belum ada kupon yang dibuat</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <button class="btn btn-primary fw-bold">+ Tambah Kupon</button>
                        </div>
                    </div>
                    <div class="row mt-1" id="referal_container" style="display: none;">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="mb-1 fw-medium">Kode Referal</p>
                                <div> {!! $donations->links() !!} </div>
                            </div>
                            <table class="table table-bordered shadow text-center">
                                <thead>
                                    <tr class="bg-primary">
                                        <th>Kode</th>
                                        <th>Anggota Terkait</th>
                                        <th>Terpakai/Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($donations->isNotEmpty())
                                        @foreach($donations as $donation)
                                        <tr>
                                            <td>{{ $donation->user->name }}</td>
                                            <td>{{ $donation->user->no_handphone }}</td>
                                            <td>{{ $donation->jumlah }}</td>
                                            <td class="d-flex justify-content-center gap-3">
                                                <a href="{{ route('events.edit', $concert->id) }}" class="btn btn-primary">Ubah</a>
                                                <form action="{{ route('events.destroy', $concert->id) }}" method="POST" enctype="multipart/form-data" class="mb-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-primary">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4">Belum ada kode referal yang dibuat</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <button class="btn btn-primary fw-bold">+ Tambah Referal</button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h5>Seating Plan</h5>
                    <div class="seating-plan-container d-none" id="imageContainer">
                        <img src="" alt="Seating Plan Image" id="previewImage" class="img-fluid">
                        <div class="seating-plan-overlay">
                            <button type="button" class="btn btn-sm btn-danger delete-btn"><i class="fa-solid fa-trash"></i></button>
                            <button type="button" class="btn btn-sm btn-primary edit-btn"><i class="fa-solid fa-pen"></i></button>
                        </div>
                    </div>

                    <div class="file-drop-area mt-3" id="dropArea">
                        <i class="bi bi-cloud-upload file-drop-icon"></i>
                        <p class="mt-2">Kasih foto seating plan pada konser ini</p>
                        <input type="file" name="gambar" id="fileInput" class="d-none" accept="image/*">
                        <p id="fileName" class="mt-2 text-muted">Belum ada file yang dipilih</p>
                    </div>
                </div>

                <div class="mb-3">
                    <h5>Deskripsi Konser</h5>
                    <textarea name="deskripsi" class="form-control" placeholder="" rows="5" maxlength="1000" id="deskripsi-area" required>{{ old('deskripsi', $concert->deskripsi ?? '') }}</textarea>
                </div>

                <div>
                    <h5>Syarat dan Ketentuan</h5>
                    <textarea name="syarat_ketentuan" class="form-control" placeholder="" rows="5" maxlength="1000" id="syarat_ketentuan-area" required>{{ old('syarat_ketentuan', $concert->syarat_ketentuan ?? '') }}</textarea>
                </div>
            </div>
            <div class="tab-pane fade" id="feedback" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Feedback</h5>
                    <div> {!! $feedbacks->links() !!} </div>
                </div>
                @if($feedbacks->isNotEmpty())
                    @foreach($feedbacks as $feedback)
                        <div class="row">
                            <div class="col-12">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <p>{{ $feedback->isi }}</p>
                                        <p class="mb-0 text-end fw-medium">{{ $feedback->user->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <p class="text-center mb-0">Feedback masih kosong</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus <span id="deleteItemName"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        //Font Bold Navbar
        let navLinks = document.querySelectorAll(".navbar-nav .nav-link");

        function updateActiveTab() {
            navLinks.forEach(link => link.classList.remove("fw-bold"));
            let activeTab = document.querySelector(".navbar-nav .nav-link.active");
            if (activeTab) {
                activeTab.classList.add("fw-bold");
            }
        }
        updateActiveTab();
        navLinks.forEach(link => {
            link.addEventListener("shown.bs.tab", function() {
                updateActiveTab();
            });
        });


        //Modal Jenis Tiket
        document.getElementById('loadCreateForm').addEventListener('click', function() {
            let concertId = this.dataset.concertId;
            fetch('{{ route("ticket-types.create") }}')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('modalContainer').innerHTML = html;
                    document.getElementById('createTicketTypeModal').querySelector('#concerts_id').value = concertId;
                    new bootstrap.Modal(document.getElementById('createTicketTypeModal')).show();
                });
        });
        document.querySelectorAll(".loadEditForm").forEach((button) => {
            button.addEventListener("click", function() {
                let ticketTypeId = this.dataset.id; // Get the ticket type ID from the button
                let concertId = this.dataset.concertId;
                let editUrl = `{{ route('ticket-types.edit', ':id') }}`.replace(':id', ticketTypeId);

                fetch(editUrl)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById("modalContainer").innerHTML = html;
                        document.getElementById('editTicketTypeModal').querySelector('#concerts_id').value = concertId;
                        let editModal = document.getElementById("editTicketTypeModal");
                        if (editModal) {
                            new bootstrap.Modal(editModal).show();
                        } else {
                            console.error("Modal element #editTicketTypeModal not found.");
                        }
                    })
                    .catch(error => console.error("Error loading modal:", error));
            });
        });


        //Show check in button
        document.querySelectorAll(".loadCheckInForm").forEach((button) => {
            button.addEventListener("click", function() {
                let purchaseId = this.dataset.purchaseId;
                let checkInShowUrl = `{{ route('events.checkInShow', ':id') }}`.replace(':id', purchaseId);

                fetch(checkInShowUrl)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById("modalContainer").innerHTML = html;
                        let checkInModal = document.getElementById("checkInModal");
                        if (checkInModal) {
                            new bootstrap.Modal(checkInModal).show();
                            //Check in button di modalnya
                            document.querySelectorAll(".btn-check-in").forEach((button) => {
                                button.addEventListener("click", function () {
                                    let ticketId = this.dataset.ticketId;
                                    let buttonElement = this;
                                    let checkInUrl = `{{ route('tickets.checkIn', ':id') }}`.replace(':id', ticketId);

                                    fetch(checkInUrl, {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "Accept": "application/json",
                                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                                        },
                                    })
                                        .then((response) => response.json())
                                        .then((data) => {
                                            if (data.error) {
                                                alert(data.error);
                                                return;
                                            }

                                            // Update UI
                                            let row = buttonElement.closest("tr");
                                            row.querySelector("td:nth-child(3)").textContent = "Checked In"; // Update Status
                                            row.querySelector("td:nth-child(4)").textContent = data.waktu_check_in; // Update Time
                                            buttonElement.replaceWith(document.createElement("span")); // Remove Button
                                            row.querySelector("td:last-child span").textContent = "Checked In";
                                            row.querySelector("td:last-child span").classList.add("text-success");
                                        })
                                        .catch((error) => {
                                            console.error("Check-in error:", error);
                                            alert("Failed to check in. Please try again.");
                                        });
                                });
                            });
                        } else {
                            console.error("Modal element #checkInModal not found.");
                        }
                    })
                    .catch(error => console.error("Error loading modal:", error));
            });
        });


        //Button hapus
        document.querySelectorAll('.deleteBtn').forEach(button => {
            button.addEventListener('click', function() {
                let itemId = this.dataset.id;
                let itemName = this.dataset.name;
                let itemAction = this.dataset.action;

                // Set modal text dynamically
                document.getElementById('deleteItemName').textContent = itemName;

                // Set the form action dynamically
                let deleteForm = document.getElementById('deleteForm');
                deleteForm.action = itemAction;

                // Show the modal
                let modalElement = document.getElementById('deleteConfirmModal');
                let deleteModal = new bootstrap.Modal(modalElement);
                deleteModal.show();
            });
        });


        //Ebooklet container
        let ebookletYes = document.getElementById("ebooklet_yes");
        let ebookletNo = document.getElementById("ebooklet_no");
        let ebookletLinkContainer = document.getElementById("ebooklet_link_container");

        function toggleEbookletInput() {
            if (ebookletYes.checked) {
                ebookletLinkContainer.style.display = "block";
            } else {
                ebookletLinkContainer.style.display = "none";
            }
        }
        toggleEbookletInput();
        ebookletYes.addEventListener("change", toggleEbookletInput);
        ebookletNo.addEventListener("change", toggleEbookletInput);


        //Donasi Container
        let donasiYes = document.getElementById("donasi_yes");
        let donasiNo = document.getElementById("donasi_no");
        let donasiContainer = document.getElementById("donasi_container");

        function toggleDonasiTable() {
            if (donasiYes.checked) {
                donasiContainer.style.display = "block";
            } else {
                donasiContainer.style.display = "none";
            }
        }
        toggleDonasiTable();
        donasiYes.addEventListener("change", toggleDonasiTable);
        donasiNo.addEventListener("change", toggleDonasiTable);


        //Tipe Kupon dan Kupon Referal Container
        let kuponYes = document.getElementById("kupon_yes");
        let kuponNo = document.getElementById("kupon_no");
        let tipeKuponContainer = document.getElementById("tipe_kupon_container");
        let tipeKupon = document.getElementById("tipe_kupon");
        let kuponContainer = document.getElementById("kupon_container");
        let referalContainer = document.getElementById("referal_container");

        function toggleTipeKupon() {
            if (kuponYes.checked) {
                tipeKuponContainer.style.display = "block";
                toggleKuponReferalContainers();
            } else {
                tipeKuponContainer.style.display = "none";
                kuponContainer.style.display = "none";
                referalContainer.style.display = "none";
            }
        }

        function toggleKuponReferalContainers() {
            let selectedValue = tipeKupon.value;
            kuponContainer.style.display = "none";
            referalContainer.style.display = "none";
            if (selectedValue === "KUPON") {
                kuponContainer.style.display = "block";
            } else if (selectedValue === "REFERAL") {
                referalContainer.style.display = "block";
            } else if (selectedValue === "KEDUANYA") {
                kuponContainer.style.display = "block";
                referalContainer.style.display = "block";
            }
        }
        toggleKuponReferalContainers();
        toggleTipeKupon();
        kuponYes.addEventListener("change", toggleTipeKupon);
        kuponNo.addEventListener("change", toggleTipeKupon);
        tipeKupon.addEventListener("change", toggleKuponReferalContainers);


        //Gambar Seating Plan
        const fileInput = document.getElementById("fileInput");
        const dropArea = document.getElementById("dropArea");
        const imageContainer = document.getElementById("imageContainer");
        const previewImage = document.getElementById("previewImage");
        const fileNameText = document.getElementById("fileName");
        const editBtn = document.querySelector(".edit-btn");
        const deleteBtn = document.querySelector(".delete-btn");

        // Check if an image is already set (from backend)
        let existingImage = "{{ $concert->gambar ?? '' }}".trim(); // Ensure it's a string
        if (existingImage && existingImage !== "null") {
            previewImage.src = existingImage;
            imageContainer.classList.remove("d-none");
            dropArea.classList.add("d-none");
        }

        // Handle File Selection
        fileInput.addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    imageContainer.classList.remove("d-none");
                    dropArea.classList.add("d-none");
                    fileNameText.textContent = file.name; // Show selected file name
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle Edit Button (Trigger File Input)
        if (editBtn) {
            editBtn.addEventListener("click", function() {
                fileInput.click();
            });
        }

        // Handle Delete Button (Reset Image)
        if (deleteBtn) {
            deleteBtn.addEventListener("click", function() {
                previewImage.src = "";
                imageContainer.classList.add("d-none");
                dropArea.classList.remove("d-none");
                fileInput.value = ""; // Reset file input
                fileNameText.textContent = "Belum ada file yang dipilih";
            });
        }
    });
</script>
@endsection