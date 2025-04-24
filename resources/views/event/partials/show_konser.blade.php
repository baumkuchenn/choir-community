<nav class="navbar">
    <div class="d-flex flex-nowrap overflow-auto w-100 hide-scrollbar border-bottom border-black" id="navbarNav">
        <div class="navbar-nav fs-5 flex-row" role="tablist">
            <a class="nav-link active px-3" data-bs-toggle="tab" href="#form-detail" role="tab" aria-controls="detail" aria-selected="true">Detail</a>
            @can('akses-member')
                <a class="nav-link px-3" data-bs-toggle="tab" href="#penyanyi" role="tab" aria-controls="penyanyi" aria-selected="false">Penyanyi</a>
                <a class="nav-link px-3" data-bs-toggle="tab" href="#panitia" role="tab" aria-controls="panitia" aria-selected="false">Panitia</a>
            @endcan
            @if(Gate::allows('akses-eticket') || Gate::allows('akses-eticket-panitia'))
                <a class="nav-link px-3" data-bs-toggle="tab" href="#tiket" role="tab" aria-controls="tiket" aria-selected="false">Tiket</a>
                <a class="nav-link px-3" data-bs-toggle="tab" href="#feedback" role="tab" aria-controls="feedback" aria-selected="false">Feedback</a>
            @endif
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
            <div class="col-12">
                <label for="sub_kegiatan_id" class="form-label">Sub Kegiatan Dari</label>
                <select class="form-select" id="sub_kegiatan_id" name="sub_kegiatan_id">
                    <option value="" disabled selected>Pilih kegiatan utama</option>
                    @foreach ($events as $subEvent)
                        <option value="{{ $subEvent->id }}" {{ old('sub_kegiatan_id', $event->sub_kegiatan_id) == $subEvent->id ? 'selected' : '' }}>
                            {{ $subEvent->nama }}
                        </option>
                    @endforeach
                </select>
                @error('sub_kegiatan_id')
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
            <div class="col-12">
                <label for="lokasi" class="form-label">Lokasi</label>
                <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="" value="{{ old('lokasi', $event->lokasi) }}" required>
                @error('lokasi')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        @if(Gate::allows('akses-event') || Gate::allows('akses-event-panitia'))
            <div class="fixed-bottom bg-body-secondary p-3 border-top text-end">
                <button class="btn btn-primary">Simpan Perubahan</button>
            </div>
        @endif
    </form>
    @can('akses-member')
        <div class="tab-pane fade" id="penyanyi" role="tabpanel">
            <h5>Daftar Penyanyi</h5>
            <button type="button" class="btn btn-primary fw-bold create-modal" data-id="{{ $event->id }}" data-name="penyanyi" data-action="{{ route('penyanyi.create', $event->id) }}">+ Tambah Penyanyi</button>

            <table id="penyanyiTable" class="table table-bordered shadow text-center">
                <thead>
                    <tr class="bg-primary">
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Suara</th>
                        <th class="text-center">Nomor Handphone</th>
                        <th class="text-center">Jenis Kelamin</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penyanyi as $item)
                        <tr>
                            <td>{{ $item->member->user->name }}</td>
                            <td>{{ $item->suara_label }}</td>
                            <td>{{ $item->member->user->no_handphone }}</td>
                            <td>{{ $item->member->user->jenis_kelamin }}</td>
                            <td>
                                <button class="btn btn-outline-danger deleteBtn" data-name="penyanyi {{ $item->member->user->name }}" data-action="{{ route('penyanyi.destroy', $item->id) }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="panitia" role="tabpanel">
            <h5>Daftar Panitia</h5>

            <button type="button" class="btn btn-primary fw-bold create-modal" data-id="{{ $event->id }}" data-name="panitia" data-action="{{ route('panitia.create', $event->id) }}">+ Tambah Panitia</button>
            <a href="{{ route('panitia.setting', $event->id) }}" class="btn btn-outline-primary fw-bold" >Pengaturan</a>

            <table id="panitiaTable" class="table table-bordered shadow text-center">
                <thead>
                    <tr class="bg-primary">
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Divisi</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Nomor Handphone</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($panitia as $item)
                        <tr>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->jabatan?->divisi->nama }}</td>
                            <td>{{ $item->jabatan?->nama }}</td>
                            <td>{{ $item->user->no_handphone }}</td>
                            <td>
                                @if ($item->tipe == 'internal')
                                    Internal
                                @elseif ($item->tipe == 'eksternal')
                                    Eksternal
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-primary edit-modal" data-name="panitia" data-route="{{ route('panitia.edit', $item->id) }}">Ubah</button>
                                <button class="btn btn-outline-danger deleteBtn" data-name="panitia {{ $item->user->name }}" data-action="{{ route('panitia.destroy', $item->id) }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endcan
    @if(Gate::allows('akses-eticket') || Gate::allows('akses-eticket-panitia'))
        <div class="tab-pane fade mb-5" id="tiket" role="tabpanel">
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5>Daftar Pembeli Tiket</h5>
                </div>
                <table id="purchaseTable" class="table table-bordered shadow text-center">
                    <thead>
                        <tr class="bg-primary">
                            <th class="text-center">Nama</th>
                            <th class="text-center">Nomor Handphone</th>
                            <th class="text-center">Waktu Pembelian</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                    @elseif($purchase->status === 'batal')
                                        Pembayaran Dibatalkan
                                    @endif
                                </td>
                                <td class="d-flex justify-content-center gap-3">
                                    @if($totalTickets > 0)
                                        @if($checkedInCount != $totalTickets && (now()->toDateString() >= $event->tanggal_mulai && now()->toDateString() <= $event->tanggal_selesai))
                                            <button class="btn btn-primary loadCheckInForm" data-purchase-id="{{ $purchase->id }}" data-concert-id="{{ $concert->id }}">Check In</button>
                                        @endif
                                    @elseif($purchase->status === 'verifikasi' || $purchase->status === 'batal')
                                        <form action="{{ route('events.payment', $purchase->id) }}" method="POST" enctype="multipart/form-data" class="mb-0">
                                            @csrf
                                            <button class="btn btn-primary">Lihat Detail</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5>Jenis Tiket</h5>
                </div>
                <table id="ticketTypeTable" class="table table-bordered shadow text-center">
                    <thead>
                        <tr class="bg-primary">
                            <th class="text-center">Nama</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Terjual/Jumlah</th>
                            <th class="text-center">Ketersediaan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ticketTypes as $type)
                        <tr>
                            <td>{{ $type->nama }}</td>
                            <td>Rp{{ number_format($type->harga, 0, ',', '.') }}</td>
                            <td>{{ $type->terjual }}/{{ $type->jumlah }}</td>
                            <td>
                                @if ($type->visibility == 'public')
                                    Dijual
                                @elseif ($type->visibility == 'private')
                                    Tidak Dijual
                                @endif
                            </td>
                            <td class="d-flex justify-content-center gap-3">
                                @can('akses-eticket')
                                    <button class="btn btn-primary edit-modal" data-name="ticket-type" data-route="{{ route('ticket-types.edit', $type->id) }}">Ubah</button>
                                    <button class="btn btn-outline-danger deleteBtn" data-name="tiket {{ $type->nama }}" data-action="{{ route('ticket-types.destroy', $type->id) }}">Hapus</button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @can('akses-eticket')
                    <button type="button" class="btn btn-primary fw-bold create-modal" data-id="{{ $concert->id }}" data-name="ticket-type" data-action="{{ route('ticket-types.create', $event->id) }}">+ Tambah Jenis</button>
                @endcan
            </div>

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
                    <textarea name="deskripsi" class="form-control" placeholder="" rows="5" maxlength="1000" id="deskripsi-area" required>{!! old('deskripsi', $concert->deskripsi ?? '') !!}</textarea>
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
                    <textarea name="syarat_ketentuan" class="form-control" placeholder="" rows="5" maxlength="1000" id="syarat_ketentuan-area" required>{!! old('syarat_ketentuan', $concert->syarat_ketentuan ?? '') !!}</textarea>
                    <small class="text-muted d-block mt-1" id="syarat_ketentuan-counter">0/1000 karakter</small>
                    @error('syarat_ketentuan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <h5>Informasi Pembayaran</h5>
                    <div class="mb-3">
                        <label for="banks_id" class="form-label">Nama Bank</label>
                        <select class="form-select" id="banks_id" name="banks_id" required>
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
                            <select class="form-select" id="tipe_kupon" name="tipe_kupon">
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
                            </div>
                            <table id="kuponTable" class="table table-bordered shadow">
                                <thead>
                                    <tr class="bg-primary">
                                        <th class="text-center">Kode</th>
                                        <th class="text-center">Potongan Harga</th>
                                        <th class="text-center">Terpakai/Jumlah</th>
                                        <th class="text-center">Waktu Expired</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kupon as $item)
                                        <tr class="text-center">
                                            <td>{{ $item->kode }}</td>
                                            <td>Rp{{ number_format($item->potongan, 0, ',', '.') }}</td>
                                            <td>{{ $item->usedAsKupon()->where('status', 'selesai')->count() }}/{{ $item->jumlah }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->waktu_expired)->format('d-m-Y H:i') }}</td>
                                            <td class="d-flex justify-content-center gap-3">
                                                @can('akses-eticket')
                                                    <button type="button" class="btn btn-primary edit-modal" data-name="kupon" data-route="{{ route('kupon.edit', $item->id) }}">Ubah</button>
                                                    <button type="button" class="btn btn-outline-danger deleteBtn" data-name="kupon {{ $item->kode }}" data-action="{{ route('kupon.destroy', $item->id) }}">Hapus</button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @can('akses-eticket')
                                <button type="button" class="btn btn-primary fw-bold create-modal" data-id="{{ $concert->id }}" data-name="kupon" data-action="{{ route('kupon.create', ['event' => $event->id, 'tipe' => 'kupon']) }}">+ Tambah Kupon</button>
                            @endcan
                        </div>
                    </div>
                    <div class="row mt-1" id="referal_container" style="display: none;">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="mb-1 fw-medium">Kode Referal</p>
                            </div>
                            <table id="referalTable" class="table table-bordered shadow">
                                <thead>
                                    <tr class="bg-primary">
                                        <th class="text-center">Kode</th>
                                        <th class="text-center">Anggota Terkait</th>
                                        <th class="text-center">Terpakai</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($referal as $item)
                                    <tr class="text-center">
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->member->user->name }}</td>
                                        <td>{{ $item->usedAsReferal()->where('status', 'selesai')->count() }}</td>
                                        <td class="d-flex justify-content-center gap-3">
                                            @can('akses-eticket')
                                                <button type="button" class="btn btn-outline-danger deleteBtn" data-name="kode referal {{ $item->kode }}" data-action="{{ route('kupon.destroy', $item->id) }}">Hapus</button>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @can('akses-eticket')
                                <button type="button" class="btn btn-primary fw-bold create-modal" data-id="{{ $concert->id }}" data-name="referal" data-action="{{ route('kupon.create', ['event' => $event->id, 'tipe' => 'referal']) }}">+ Tambah Referal</button>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="fixed-bottom bg-body-secondary p-3 border-top text-end">
                    <button class="btn btn-primary">{{ $concert->status_label }}</button>
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
    @endif
</div>
<div id="modalContainer"></div>