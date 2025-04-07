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

        <div class="fixed-bottom bg-body-secondary p-3 border-top text-end">
            <button class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
    
    <div class="tab-pane fade" id="jadwal" role="tabpanel">
        <h5>Jadwal Latihan {{ $event->nama }}</h5>
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="form-label">Jadwal Latihan</p>
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
    </div>
</div>