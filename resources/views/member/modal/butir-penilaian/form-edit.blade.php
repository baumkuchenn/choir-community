<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Tambah Divisi Pengurus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('butir-penilaian.update', $butirPenilaian->id) }}" method="POST" class="mb-0">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="choirs_id" value="{{ $choirId }}">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $butirPenilaian->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="bobot_nilai" class="form-label">Bobot Nilai (%)</label>
                        <input type="text" class="form-control" id="bobot_nilai" name="bobot_nilai" value="{{ $butirPenilaian->bobot_nilai }}" required>
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