<div class="modal fade" id="tambahPanitiaModal" tabindex="-1" aria-labelledby="tambahPanitiaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPanitiaModalLabel">Tambah Panitia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panitia.store') }}" method="POST" class="mb-0">
                    @csrf
                    <input type="hidden" id="events_id" name="events_id" value="">
                    <div class="mb-3 d-flex align-items-center gap-3">
                        <label><input type="radio" name="mode" value="seleksi" checked> Hasil Seleksi</label>
                        <label><input type="radio" name="mode" value="baru"> Panitia Baru</label>
                        <label><input type="radio" name="mode" value="event"> Kegiatan Lain</label>
                    </div>
                    <div id="panitia-seleksi-section">
                        <div class="d-flex justify-content-start mb-2">
                            <button type="button" id="pilihSemuaBtn" class="btn btn-sm btn-secondary">Pilih Semua</button>
                        </div>
                        <table id="seleksiTable" class="table table-bordered shadow text-center">
                            <thead>
                                <tr class="bg-primary">
                                    <th class="text-center">Pilih</th>
                                    <th class="text-center">Nama Lengkap</th>
                                    <th class="text-center">Nomor Handphone</th>
                                    <th class="text-center">Jenis Kelamin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendaftar as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox-panitia" name="users_id[]" value="{{ $item->user->id }}">
                                        </td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->no_handphone }}</td>
                                        <td>{{ $item->user->jenis_kelamin }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="panitia-baru-section" style="display: none;">
                        <div class="mb-3">
                            <label for="users_id" class="form-label">Pilih Pengguna</label>
                            <select class="form-control" id="users_id" name="users_id" required disabled></select>
                        </div>
                        <div class="mb-3">
                            <label for="jabatan_id" class="form-label">Jabatan Panitia</label>
                            <select class="form-select" name="jabatan_id" required disabled>
                                <option value="">Tidak Ada</option>
                                @foreach($position as $divisi)
                                    @if(!empty($divisi['jabatans']))
                                        <optgroup label="{{ $divisi['nama'] }}">
                                            @foreach($divisi['jabatans'] as $pos)
                                                <option value="{{ $pos['id'] }}" 
                                                    {{ old('jabatan_id', $panitia->jabatan_id ?? '') == $pos['id'] ? 'selected' : '' }}>
                                                    {{ $divisi['nama'] }} - {{ $pos['nama'] }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="event-lain-section" style="display: none;">
                        <div class="mb-3">
                            <label for="event_lain_id" class="form-label">Ambil Dari Kegiatan</label>
                            <select class="form-select" id="event_lain_id" name="event_lain_id" required disabled>
                                <option value="" disabled selected>Pilih Kegiatan</option>
                                @foreach ($events as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
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