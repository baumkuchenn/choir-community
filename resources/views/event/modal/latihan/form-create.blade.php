<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Jadwal Latihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('latihans.store') }}" method="POST" class="mb-0" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="events_id" name="events_id" value="">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                        </div>
                        <div class="col-6">
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                    </div>
                    <div class="mb-3">
                        <label for="kegiatan_berulang" class="form-label">Kegiatan Berulang</label>
                        <select class="form-select" id="kegiatan_berulang" name="kegiatan_berulang" required>
                            <option value="ya">Ya</option>
                            <option value="tidak" selected>Tidak</option>
                        </select>
                    </div>
                    <div id="perulangan_wrapper">
                        <div class="row mb-3">
                            <label for="interval" class="form-label">Ulangi Setiap</label>
                            <div class="col-6">
                                <input type="number" class="form-control" id="interval" name="interval" required>
                            </div>
                            <div class="col-6">
                                <select class="form-select" id="frekuensi" name="frekuensi" required>
                                    <option value="minggu" selected>Minggu</option>
                                    <option value="bulan" selected>Bulan</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pada Hari</label>
                            <div class="row">
                                @foreach(array_chunk($hari, 3, true) as $chunk)
                                    <div class="col-6 col-md-4">
                                        @foreach($chunk as $key => $label)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="hari[]" id="hari_{{ $key }}" value="{{ $key }}">
                                                <label class="form-check-label" for="hari_{{ $key }}">{{ $label }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Berhenti</label>
                            <div class="row">
                                @foreach($tipeSelesai as $key => $label)
                                    <div class="col-12">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input class="form-check-input" type="radio" name="tipe_selesai" id="tipe_{{ $key }}" value="{{ $key }}">
                                            @if ($key == 'jumlah')
                                                <input type="number" name="jumlah_perulangan" id="jumlah_perulangan" class="form-control" style="width: fit-content;" disabled>
                                            @endif
                                            <label class="form-check-label" for="tipe_{{ $key }}">
                                                {{ $label }}
                                            </label>
                                            @if ($key == 'tanggal')
                                                <input type="date" name="tanggal_berakhir" id="tanggal_berakhir" class="form-control" style="width: fit-content;" disabled>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="parent_notification" name="parent_notification" value="ya">
                            <label class="form-check-label" for="parent_notification">
                                Kirim notifikasi ke anggota komunitas yang ikut dalam kegiatan utama.
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>