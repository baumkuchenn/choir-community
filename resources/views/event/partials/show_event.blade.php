<nav class="navbar">
    <div class="d-flex flex-nowrap overflow-auto w-100 hide-scrollbar border-bottom border-black" id="navbarNav">
        <div class="navbar-nav fs-5 flex-row" role="tablist">
            <a class="nav-link active px-3" data-bs-toggle="tab" href="#form-detail" role="tab" aria-controls="detail" aria-selected="true">Detail</a>
            @can('akses-member')
                @if($event->peran == 'penyanyi' || $event->peran == 'keduanya')
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#penyanyi" role="tab" aria-controls="penyanyi" aria-selected="false">Penyanyi</a>
                @elseif($event->peran == 'panitia' || $event->peran == 'keduanya')
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#panitia" role="tab" aria-controls="panitia" aria-selected="false">Panitia</a>
                @endif
            @endcan
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
                <label for="parent_id" class="form-label">Sub Kegiatan Dari</label>
                <select class="form-select" id="parent_id" name="parent_id">
                    <option value="" disabled selected>Pilih kegiatan utama</option>
                    @foreach ($events as $subEvent)
                        <option value="{{ $subEvent->id }}" {{ old('parent_id', $event->parent_id) == $subEvent->id ? 'selected' : '' }}>
                            {{ $subEvent->nama }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
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
            <div class="row mb-3">
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="notification" name="notification" value="ya">
                        <label class="form-check-label" for="notification">
                            Kirim notifikasi perubahan ke anggota komunitas yang terkait.
                        </label>
                    </div>
                </div>
            </div>
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
</div>
<div id="modalContainer"></div>