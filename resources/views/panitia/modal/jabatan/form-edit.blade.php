<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Tambah Divisi Pengurus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('positions.update', $jabatan->id) }}" method="POST" class="mb-0">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $jabatan->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="divisions_id" class="form-label">Divisi</label>
                        <select id="divisions_id" name="divisions_id" class="form-select" required>
                            <option value="">-- Pilih Divisi --</option>
                            @foreach($divisi as $item)
                                <option value="{{ $item->id }}"
                                {{ ($jabatan->divisions_id ?? '') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Hak Akses:</label>
                        <div class="row">
                            @php
                                $akses = [
                                    'akses_member' => 'Manajemen Anggota',
                                    'akses_event' => 'Manajemen Kegiatan',
                                    'akses_roles' => 'Manajemen Jabatan',
                                    'akses_eticket' => 'E-ticketing',
                                    'akses_forum' => 'Forum',
                                ];
                            @endphp

                            @foreach(array_chunk($akses, 3, true) as $chunk)
                                <div class="col-md-6">
                                    @foreach($chunk as $key => $label)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="{{ $key }}" id="{{ $key }}" value="1"
                                                {{ ($jabatan->$key ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
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