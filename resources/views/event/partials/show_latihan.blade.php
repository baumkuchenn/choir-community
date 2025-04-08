<nav class="navbar">
    <div class="d-flex flex-nowrap overflow-auto w-100 hide-scrollbar border-bottom border-black" id="navbarNav">
        <div class="navbar-nav fs-5 flex-row" role="tablist">
            <a class="nav-link active px-3" data-bs-toggle="tab" href="#form-detail" role="tab" aria-controls="detail" aria-selected="true">Detail</a>
            <a class="nav-link px-3" data-bs-toggle="tab" href="#jadwal" role="tab" aria-controls="jadwal" aria-selected="false">Jadwal Latihan</a>
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

        <div class="fixed-bottom bg-body-secondary p-3 border-top text-end">
            <button class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
    
    <div class="tab-pane fade" id="jadwal" role="tabpanel">
        <h5>Jadwal {{ $event->nama }}</h5>
        <div class="mb-3">
            <table id="latihanTable" class="table table-bordered shadow text-center">
                <thead>
                    <tr class="bg-primary">
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Jam Mulai</th>
                        <th class="text-center">Jam Selesai</th>
                        <th class="text-center">Lokasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latihan as $item)
                        <tr>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} WIB</td>
                            <td>{{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }} WIB</td>
                            <td>{{ $item->lokasi }}</td>
                            <td class="d-flex justify-content-center gap-3">
                                <button class="btn btn-primary loadEditForm" data-id="{{ $item->id }}" data-event-id="{{ $event->id }}" data-tanggal-mulai="{{ $event->tanggal_mulai }}" data-tanggal-selesai="{{ $event->tanggal_selesai }}">Ubah</button>
                                <button class="btn btn-outline-danger deleteBtn" data-name="latihan tanggal {{ $item->tanggal }}" data-action="{{ route('latihans.destroy', $item->id) }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="btn btn-primary fw-bold" id="loadCreateForm" data-id="{{ $event->id }}" data-tanggal-mulai="{{ $event->tanggal_mulai }}" data-tanggal-selesai="{{ $event->tanggal_selesai }}" data-action="{{ route('latihans.create') }}">+ Tambah Jadwal</button>
        </div>
        <div id="modalContainer"></div>
    </div>
</div>