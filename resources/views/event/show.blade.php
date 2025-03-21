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
                    <a class="nav-link active px-3" data-bs-toggle="tab" href="#form-detail" role="tab" aria-controls="detail" aria-selected="true">Detail</a>
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#penyanyi" role="tab" aria-controls="penyanyi" aria-selected="false">Penyanyi</a>
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#panitia" role="tab" aria-controls="panitia" aria-selected="false">Panitia</a>
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#tiket" role="tab" aria-controls="tiket" aria-selected="false">Tiket</a>
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#feedback" role="tab" aria-controls="feedback" aria-selected="false">Feedback</a>
                </div>
            </div>
        </nav>

        <style>
            .hide-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }
        </style>

        <div class="tab-content">
            <form method="POST" action="{{ route('events.update', $event->id) }}" class="tab-pane fade show active mb-5" id="form-detail" role="tabpanel">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="nama" class="form-label">Nama Kegiatan</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="" value="{{ old('nama', $event->nama) }}" required>
                        @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" placeholder="" value="{{ old('tanggal_mulai', $event->tanggal_mulai) }}" required>
                        @error('tanggal_mulai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" placeholder="" value="{{ old('tanggal_selesai', $event->tanggal_selesai) }}" required>
                        @error('tanggal_selesai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" placeholder="" value="{{ old('jam_mulai', $event->jam_mulai) }}" required>
                        @error('jam_mulai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" placeholder="" value="{{ old('jam_selesai', $event->jam_selesai) }}" required>
                        @error('jam_selesai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label for="tanggal_gladi" class="form-label">Tanggal Gladi</label>
                        <input type="date" class="form-control" id="tanggal_gladi" name="tanggal_gladi" placeholder="" value="{{ old('tanggal_gladi', $event->tanggal_gladi) }}">
                    </div>
                    <div class="col-6">
                        <label for="jam_gladi" class="form-label">Jam Gladi</label>
                        <input type="time" class="form-control" id="jam_gladi" name="jam_gladi" placeholder="" value="{{ old('jam_gladi', $event->jam_gladi) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="" value="{{ old('lokasi', $event->lokasi) }}" required>
                        @error('lokasi')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="form-label">Jadwal Latihan</p>
                            <div> {!! $donations->links() !!} </div>
                        </div>
                        <table class="table table-bordered shadow text-center">
                            <thead>
                                <tr class="bg-primary">
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Lokasi</th>
                                    <th>Aksi</th>
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
                                        <td colspan="4">Belum ada jadwal latihan untuk kegiatan ini</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <!-- <button class="btn btn-primary fw-bold" id="loadCreateForm" data-id="{{ $event->id }}" data-action="{{ route('ticket-types.create') }}">+ Tambah Latihan</button> -->
                    </div>
                </div>

                <div class="fixed-bottom bg-body-secondary p-3 border-top text-end">
                    <button class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
            <div class="tab-pane fade" id="penyanyi" role="tabpanel">
                <h5>Daftar Penyanyi</h5>
                <p>Isi daftar penyanyi di sini...</p>
            </div>
            <div class="tab-pane fade" id="panitia" role="tabpanel">
                <h5>Daftar Panitia</h5>
                <p>Isi daftar panitia di sini...</p>
            </div>
            <div class="tab-pane fade mb-5" id="tiket" role="tabpanel">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Daftar Pembeli Tiket</h5>
                    </div>
                    <table id="purchaseTable" class="table table-bordered shadow text-center">
                        <thead class="text-center">
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
                                            $checkedInCount = $tickets->where('check_in', 'ya')->count();
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
                                            @elseif($purchase->status === 'verifikasi')
                                                Verifikasi Pembayaran
                                            @endif
                                        </td>
                                        <td class="d-flex justify-content-center gap-3">
                                            @if($totalTickets > 0)
                                                @if($checkedInCount != $totalTickets)
                                                    <button class="btn btn-primary loadCheckInForm" data-purchase-id="{{ $purchase->id }}" data-concert-id="{{ $concert->id }}">Check In</button>
                                                @endif
                                            @elseif($purchase->status === 'verifikasi')
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
                    <div class="d-flex justify-content-between align-items-center">
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
                                        <button class="btn btn-outline-danger deleteBtn" data-name="tiket {{ $type->nama }}" data-action="{{ route('ticket-types.destroy', $type->id) }}">Hapus</button>
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
                    <button class="btn btn-primary fw-bold" id="loadCreateForm" data-id="{{ $concert->id }}" data-name="concerts-id" data-action="{{ route('ticket-types.create') }}">+ Tambah Jenis</button>
                </div>

                <div id="modalContainer"></div>

                <form method="POST" action="{{ route('concerts.update', $event->id) }}" class="mb-0" id="form-tiket" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <h5>Gambar Poster/Banner Konser</h5>
                        <div class="file-drop-area mt-3" id="dropArea">
                            <i class="bi bi-cloud-upload file-drop-icon"></i>
                            <p class="mt-2">
                                Seret & taruh gambar poster/banner konser di sini atau 
                                <label for="fileInput" class="text-primary fw-bold" style="cursor: pointer;">cari</label>
                            </p>
                            <input type="file" name="gambar" id="fileInput" class="d-none" accept="image/*">
                            <input type="hidden" name="existing_gambar" id="existingSeatingPlan" value="{{ $concert->gambar ?? '' }}">
                            <p id="fileName" class="mt-2 text-muted">Belum ada file yang dipilih. Maksimal ukuran file 2 MB. Rekomendasi gambar memiliki rasio aspek 16:9</p>
                        </div>

                        <div class="text-center">
                            <div class="image-container d-none" id="imageContainer">
                                <img src="" alt="Concert Banner/Poster Image" id="previewImage" class="img-fluid">
                                <div class="image-overlay">
                                    <button type="button" class="btn btn-sm btn-danger delete-btn"><i class="fa-solid fa-trash"></i></button>
                                    <button type="button" class="btn btn-sm btn-primary edit-btn"><i class="fa-solid fa-pen"></i></button>
                                </div>
                            </div>
                        </div>
                        @error('gambar')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <h5>Deskripsi Konser</h5>
                        <textarea name="deskripsi" class="form-control" placeholder="" rows="5" maxlength="1000" id="deskripsi-area" required>{{ old('deskripsi', $concert->deskripsi ?? '') }}</textarea>
                        <small class="text-muted d-block mt-1" id="deskripsi-counter">0/1000 karakter</small>
                        @error('deskripsi')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <h5>Seating Plan</h5>
                        <div class="file-drop-area mt-3" id="dropArea">
                            <i class="bi bi-cloud-upload file-drop-icon"></i>
                            <p class="mt-2">
                                Seret & taruh gambar seating plan di sini atau 
                                <label for="fileInput" class="text-primary fw-bold" style="cursor: pointer;">cari</label>
                            </p>
                            <input type="file" name="seating_plan" id="fileInput" class="d-none" accept="image/*">
                            <input type="hidden" name="existing_seating_plan" id="existingSeatingPlan" value="{{ $concert->seating_plan ?? '' }}">
                            <p id="fileName" class="mt-2 text-muted">Belum ada file yang dipilih. Maksimal ukuran file 2 MB. Rekomendasi gambar memiliki rasio aspek 16:9</p>
                        </div>

                        <div class="text-center">
                            <div class="image-container d-none" id="imageContainer">
                                <img src="" alt="Seating Plan Image" id="previewImage" class="img-fluid">
                                <div class="image-overlay">
                                    <button type="button" class="btn btn-sm btn-danger delete-btn"><i class="fa-solid fa-trash"></i></button>
                                    <button type="button" class="btn btn-sm btn-primary edit-btn"><i class="fa-solid fa-pen"></i></button>
                                </div>
                            </div>
                        </div>
                        @error('seating_plan')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <h5>Syarat dan Ketentuan</h5>
                        <textarea name="syarat_ketentuan" class="form-control" placeholder="" rows="5" maxlength="1000" id="syarat_ketentuan-area" required>{{ old('syarat_ketentuan', $concert->syarat_ketentuan ?? '') }}</textarea>
                        <small class="text-muted d-block mt-1" id="syarat_ketentuan-counter">0/1000 karakter</small>
                        @error('syarat_ketentuan')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <h5>Informasi Pembayaran</h5>
                        <div class="mb-3">
                            <label for="banks_id" class="form-label">Nama Bank</label>
                            <select class="form-control" id="banks_id" name="banks_id" required>
                                <option value="" disabled selected>Pilih Bank</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}" 
                                        {{ old('banks_id', $concert->banks_id) == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('banks_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="no_rekening" class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control" id="no_rekening" name="no_rekening" placeholder="" value="{{ old('no_rekening', $concert->no_rekening) }}" required>
                            @error('no_rekening')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        

                        <div class="mb-3">
                            <label for="pemilik_rekening" class="form-label">Nama Pemilik Rekening</label>
                            <input type="text" class="form-control" id="pemilik_rekening" name="pemilik_rekening" placeholder="" value="{{ old('pemilik_rekening', $concert->pemilik_rekening) }}" required>
                            @error('pemilik_rekening')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="berita_transfer" class="form-label">Keterangan Berita Transfer</label>
                            <input type="text" class="form-control" id="berita_transfer" name="berita_transfer" placeholder="" value="{{ old('berita_transfer', $concert->berita_transfer) }}" required>
                            @error('berita_transfer')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <h5>E-Booklet</h5>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <p class="mb-0">Menggunakan e-booklet?</p>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ebooklet" id="ebooklet_yes" value="ya"
                                        {{ old('ebooklet', $concert->ebooklet) == 'ya' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ebooklet_yes">Ya</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ebooklet" id="ebooklet_no" value="tidak"
                                        {{ old('ebooklet', $concert->ebooklet) == 'tidak' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ebooklet_no">Tidak</label>
                                </div>
                                @error('ebooklet')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-8" id="ebooklet_link_container" style="display: none;">
                                <label for="link_ebooklet" class="form-label">Link e-booklet</label>
                                <input type="text" class="form-control" id="link_ebooklet" name="link_ebooklet" placeholder=""
                                    value="{{ old('link_ebooklet', $concert->link_ebooklet) }}">
                                @error('link_ebooklet')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h5>Donasi</h5>
                        <div class="row">
                            <div class="col-12">
                                <p class="mb-0">Menerima donasi?</p>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="donasi" id="donasi_yes" value="ya"
                                        {{ old('donasi', $concert->donasi == 'ya') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="donasi_yes">Ya</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="donasi" id="donasi_no" value="tidak"
                                        {{ old('donasi', $concert->donasi == 'tidak') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="donasi_no">Tidak</label>
                                </div>
                                @error('donasi')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
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
                                    <input class="form-check-input" type="radio" name="kupon" id="kupon_yes" value="ya"
                                        {{ old('kupon', $concert->kupon == 'ya') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="kupon_yes">Ya</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kupon" id="kupon_no" value="tidak"
                                        {{ old('kupon', $concert->kupon == 'tidak') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="kupon_no">Tidak</label>
                                </div>
                                @error('kupon')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-8" id="tipe_kupon_container" style="display: none;">
                                <label for="tipe_kupon" class="form-label">Tipe kupon yang digunakan</label>
                                <select class="form-control" id="tipe_kupon" name="tipe_kupon">
                                    <option value="" disabled selected>Pilih tipe kupon</option>
                                    <option value="kupon" {{ old('tipe_kupon', $concert->tipe_kupon) == 'kupon' ? 'selected' : '' }}>Kupon</option>
                                    <option value="referal" {{ old('tipe_kupon', $concert->tipe_kupon) == 'referal' ? 'selected' : '' }}>Referal</option>
                                    <option value="keduanya" {{ old('tipe_kupon', $concert->tipe_kupon) == 'keduanya' ? 'selected' : '' }}>Keduanya</option>
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

                    <div class="fixed-bottom bg-body-secondary p-3 border-top text-end">
                        <button class="btn btn-primary">
                            @if($concert->status == 'draft')    
                                Upload ke E-ticketing
                            @else
                                Simpan Perubahan
                            @endif
                        </button>
                    </div>
                </form>
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
            let route = this.dataset.action;
            let name = this.dataset.name;
            let id = this.dataset.id;
            fetch(route)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('modalContainer').innerHTML = html;
                    if(name === 'concerts-id'){
                        document.getElementById('createModal').querySelector('#concerts_id').value = id;
                    }
                    new bootstrap.Modal(document.getElementById('createModal')).show();
                });
        });

        document.querySelectorAll(".loadEditForm").forEach((button) => {
            button.addEventListener("click", function() {
                let ticketTypeId = this.dataset.id;
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


        //Data table pembeli tiket untuk search bar
        $('#purchaseTable').DataTable({
            "paging": true,         // Enable pagination
            "searching": true,      // Enable search box
            "ordering": false,       // Enable column sorting
            "info": false,           // Show info text
            "lengthMenu": [10, 25, 50, 100], // Control how many entries to show
            "language": {
                "search": "Cari: ", // Customize search box placeholder
                "lengthMenu": "Tampilkan _MENU_ tiket per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(disaring dari _MAX_ total data)",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            },
            "columnDefs": [
                { "className": "text-center", "targets": "_all" } // Centers all columns
            ]
        });
        $('.dataTables_length').addClass('mb-2');
        $('.dataTables_filter').addClass('mb-2');


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


        //Counter text area
        const deksripsiInput = document.getElementById("deskripsi-area");
        const syaratInput = document.getElementById("syarat_ketentuan-area");
        const deskripsiCount = document.getElementById("deskripsi-counter");
        const syaratCount = document.getElementById("syarat_ketentuan-counter");

        function updateCounter(inputElement, counterElement) {
            counterElement.textContent = `${inputElement.value.length}/1000 karakter`;
        }

        updateCounter(deksripsiInput, deskripsiCount);
        updateCounter(syaratInput, syaratCount);

        deksripsiInput.addEventListener("input", function() {
            updateCounter(deksripsiInput, deskripsiCount);
        });

        syaratInput.addEventListener("input", function() {
            updateCounter(syaratInput, syaratCount);
        });
        

        //Ebooklet container
        let ebookletYes = document.getElementById("ebooklet_yes");
        let ebookletNo = document.getElementById("ebooklet_no");
        let ebookletLinkContainer = document.getElementById("ebooklet_link_container");
        let ebookletInput = document.getElementById("link_ebooklet");

        function toggleEbookletInput() {
            if (ebookletYes.checked) {
                ebookletLinkContainer.style.display = "block";
                ebookletInput.setAttribute("required", "required");
            } else {
                ebookletLinkContainer.style.display = "none";
                ebookletInput.removeAttribute("required");
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
                tipeKupon.setAttribute("required", "required");
                toggleKuponReferalContainers();
            } else {
                tipeKuponContainer.style.display = "none";
                tipeKupon.removeAttribute("required");
                kuponContainer.style.display = "none";
                referalContainer.style.display = "none";
            }
        }
        
        function toggleKuponReferalContainers() {
            let selectedValue = tipeKupon.value;
            kuponContainer.style.display = "none";
            referalContainer.style.display = "none";
            if (selectedValue === "kupon") {
                kuponContainer.style.display = "block";
            } else if (selectedValue === "referal") {
                referalContainer.style.display = "block";
            } else if (selectedValue === "keduanya") {
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
        document.querySelectorAll(".file-drop-area").forEach((dropArea) => {
            const fileInput = dropArea.querySelector("input[type='file']");
            const fileNameDisplay = dropArea.querySelector("#fileName");
            const imageContainer = dropArea.closest(".mb-3").querySelector(".image-container");
            const previewImage = imageContainer.querySelector("img");
            const editBtn = imageContainer.querySelector(".edit-btn");
            const deleteBtn = imageContainer.querySelector(".delete-btn");
            const existingImageInput = dropArea.querySelector("input[type='hidden']");
            
            // Load existing image
            let checkImage = existingImageInput.value.trim();
            if (checkImage && checkImage !== "null") {
                let existingImage = `{{ asset('storage/') }}/${checkImage}`;
                previewImage.src = existingImage;
                imageContainer.classList.remove("d-none");
                dropArea.classList.add("d-none");
            }

            // Click to browse
            dropArea.addEventListener("click", () => fileInput.click());

            // Handle file selection
            fileInput.addEventListener("change", (event) => {
                updateFileDisplay(event.target.files[0]);
            });

            // Drag over effect
            dropArea.addEventListener("dragover", (event) => {
                event.preventDefault();
                dropArea.classList.add("dragover");
            });

            dropArea.addEventListener("dragleave", () => {
                dropArea.classList.remove("dragover");
            });

            // Drop file
            dropArea.addEventListener("drop", (event) => {
                event.preventDefault();
                dropArea.classList.remove("dragover");
                const file = event.dataTransfer.files[0];
                fileInput.files = event.dataTransfer.files; // Update input
                updateFileDisplay(file);
            });

            // Update File Display
            function updateFileDisplay(file) {
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        imageContainer.classList.remove("d-none");
                        dropArea.classList.add("d-none");
                        fileNameDisplay.textContent = file.name;
                        existingImageInput.value = ""; // Clear existing image
                    };
                    reader.readAsDataURL(file);
                } else {
                    fileNameDisplay.textContent = "Belum ada file yang dipilih";
                }
            }

            // Edit Button (Re-upload)
            if (editBtn) {
                editBtn.addEventListener("click", function () {
                    fileInput.click();
                });
            }

            // Delete Button (Remove Image)
            if (deleteBtn) {
                deleteBtn.addEventListener("click", function () {
                    previewImage.src = "";
                    imageContainer.classList.add("d-none");
                    dropArea.classList.remove("d-none");
                    fileInput.value = "";
                    fileNameDisplay.textContent = "Belum ada file yang dipilih";
                    existingImageInput.value = existingImage; // Restore old image value if deleted
                });
            }
        });
    });
</script>
@endsection