<div class="modal fade" id="tambahPenyanyiModal" tabindex="-1" aria-labelledby="tambahPenyanyiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPenyanyiModalLabel">Tambah Penyanyi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('penyanyi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="events_id" name="events_id" value="">
                    <div class="mb-3">
                        <label for="members_id" class="form-label">Pilih Anggota</label>
                        <select class="form-control" id="members_id" name="members_id" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="suara" class="form-label">Kategori Suara</label>
                        <select class="form-select" id="suara" name="suara" required>
                            <option value="" disabled>Pilih Suara</option>
                            @if($choir->tipe == 'SSAA' || $choir->tipe == 'SSAATTBB')
                                <option value="sopran_1" {{ old('suara') == 'sopran_1' ? 'selected' : '' }}>Sopran 1</option>
                                <option value="sopran_2" {{ old('suara') == 'sopran_2' ? 'selected' : '' }}>Sopran 2</option>
                                <option value="alto_1" {{ old('suara') == 'alto_1' ? 'selected' : '' }}>Alto 1</option>
                                <option value="alto_2" {{ old('suara') == 'alto_2' ? 'selected' : '' }}>Alto 2</option>
                            @endif
                            @if($choir->tipe == 'TTBB' || $choir->tipe == 'SSAATTBB')
                                <option value="tenor_1" {{ old('suara') == 'tenor_1' ? 'selected' : '' }}>Tenor 1</option>
                                <option value="tenor_2" {{ old('suara') == 'tenor_2' ? 'selected' : '' }}>Tenor 2</option>
                                <option value="bass_1" {{ old('suara') == 'bass_1' ? 'selected' : '' }}>Bass 1</option>
                                <option value="bass_2" {{ old('suara') == 'bass_2' ? 'selected' : '' }}>Bass 2</option>
                            @endif
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>