<nav class="navbar">
    <div class="d-flex flex-nowrap overflow-auto w-100 hide-scrollbar border-bottom border-black" id="navbarNav">
        <div class="navbar-nav fs-5 flex-row" role="tablist">
            <a class="nav-link active px-3" data-bs-toggle="tab" href="#form-detail" role="tab" aria-controls="detail" aria-selected="true">Detail</a>
            <a class="nav-link px-3" data-bs-toggle="tab" href="#pendaftar" role="tab" aria-controls="pendaftar" aria-selected="false">Pendaftar</a>
            <a class="nav-link px-3" data-bs-toggle="tab" href="#hasil" role="tab" aria-controls="hasil" aria-selected="false">Hasil</a>
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

        <div class="row">
            <div class="col-12">
                <input class="form-check-input" type="checkbox" id="all_notification" name="all_notification" value="ya">
                <label class="form-check-label" for="all_notification">Kirim notifikasi perubahan ke seluruh anggota komunitas.</label>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <input class="form-check-input" type="checkbox" id="parent_notification" name="parent_notification" value="ya">
                <label class="form-check-label" for="parent_notification">Kirim notifikasi perubahan ke anggota komunitas yang ikut dalam kegiatan utama.</label>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <input class="form-check-input" type="checkbox" id="parent_display" name="parent_display" value="ya">
                <label class="form-check-label" for="parent_display">Hanya tampilkan kegiatan ke anggota komunitas yang ikut dalam kegiatan utama.</label>
            </div>
        </div>

        <div class="fixed-bottom bg-body-secondary p-3 border-top text-end">
            <button class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
    <div class="tab-pane fade" id="pendaftar" role="tabpanel">
        @if ($seleksi->tipe == 'event')
            <h5>Daftar Seleksi Penyanyi</h5>
            <button type="button" class="btn btn-primary mb-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                + Tambah Anggota Baru
            </button>
            <a href="{{ route('members.setting') }}" class="btn btn-outline-primary mb-3 fw-bold" >Pengaturan</a>
            <table id="pendaftarTable" class="table table-bordered shadow text-center">
                <thead>
                    <tr class="bg-primary">
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Nomor Handphone</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Jenis Kelamin</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendaftar as $item)
                        <tr>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->user->no_handphone }}</td>
                            <td>{{ $item->user->email }}</td>
                            <td>{{ $item->user->jenis_kelamin }}</td>
                            <td>
                                @if ($item->nilais->isEmpty())
                                    <a href="{{ route('seleksi.wawancara', ['seleksi' => $item->seleksi->id, 'user' => $item->user->id]) }}" class="btn btn-primary">Lihat Detail</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif ($seleksi->tipe == 'panitia')
            <h5>Daftar Seleksi Panitia</h5>
            <button type="button" class="btn btn-primary mb-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                + Tambah Anggota Baru
            </button>
            
            <table id="pendaftarTable" class="table table-bordered shadow text-center">
                <thead>
                    <tr class="bg-primary">
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Nomor Handphone</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Jenis Kelamin</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendaftar as $item)
                        <tr>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->user->no_handphone }}</td>
                            <td>{{ $item->user->email }}</td>
                            <td>{{ $item->user->jenis_kelamin }}</td>
                            <td>
                                @if ($item->tipe == 'internal')
                                    Internal
                                @elseif ($item->tipe == 'eksternal')
                                    Eksternal
                                @endif
                            </td>
                            <td>
                                @if (empty($item->hasil_wawancara))
                                    <a href="{{ route('seleksi.wawancara', ['seleksi' => $item->seleksi->id, 'user' => $item->user->id]) }}" class="btn btn-primary">Lihat Detail</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="tab-pane fade" id="hasil" role="tabpanel">
        @if ($seleksi->tipe == 'event')
            <h5>Hasil Seleksi Penyanyi</h5>
            <table id="hasilTable" class="table table-bordered shadow text-center">
                <thead>
                    <tr class="bg-primary">
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Nomor Handphone</th>
                        <th class="text-center">Suara</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil as $item)
                        <tr>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->user->no_handphone }}</td>
                            <td>{{ $item->kategori_suara_label }}</td>
                            <td>{{ $item->nilais->sum('pivot.nilai') }}</td>
                            <td>{{ $item->lolos_label }}</td>
                            <td>
                                <a href="{{ route('seleksi.wawancara', ['seleksi' => $item->seleksi->id, 'user' => $item->user->id]) }}" class="btn btn-primary">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif ($seleksi->tipe == 'panitia')
            <h5>Hasil Seleksi Panitia</h5>
            <table id="hasilTable" class="table table-bordered shadow text-center">
                <thead>
                    <tr class="bg-primary">
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Nomor Handphone</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil as $item)
                        <tr>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->user->no_handphone }}</td>
                            <td>{{ $item->user->email }}</td>
                            <td>{{ $item->tipe_label }}</td>
                            <td>{{ $item->lolos_label }}</td>
                            <td>
                                <a href="{{ route('seleksi.wawancara', ['seleksi' => $item->seleksi->id, 'user' => $item->user->id]) }}" class="btn btn-primary">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@include('member.seleksi.modal.form-add', ['seleksi' => $seleksi, 'event' => $event])