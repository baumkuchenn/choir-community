<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Jabatan Pengurus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panitia-jabatan.store') }}" method="POST" class="mb-0">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="divisi_id" class="form-label">Divisi</label>
                        <select id="divisi_id" name="divisi_id" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Divisi --</option>
                            @foreach($divisi as $item)
                                <option value="{{ $item->id }}">
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
                                    'akses_event' => 'Manajemen Kegiatan',
                                    'akses_eticket' => 'E-ticketing',
                                ];
                            @endphp

                            <div class="col-md-6">
                                @foreach($akses as $key => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="{{ $key }}" id="{{ $key }}" value="1">
                                        <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
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