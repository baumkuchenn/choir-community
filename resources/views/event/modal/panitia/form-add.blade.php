<div class="modal fade" id="tambahPanitiaModal" tabindex="-1" aria-labelledby="tambahPanitiaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPanitiaModalLabel">Tambah Panitia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panitia.store') }}" method="POST" class="mb-0">
                    @csrf
                    <input type="hidden" id="events_id" name="events_id" value="">
                    <div class="mb-3">
                        <label for="users_id" class="form-label">Pilih Pengguna</label>
                        <select class="form-control" id="users_id" name="users_id" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan_id" class="form-label">Jabatan Pengurus</label>
                        <select class="form-select" name="jabatan_id" required>
                            <option value="">Tidak Ada</option>
                            @foreach($position as $divisi)
                                @if(!empty($divisi['jabatans']))
                                    <optgroup label="{{ $divisi['nama'] }}">
                                        @foreach($divisi['jabatans'] as $pos)
                                            <option value="{{ $pos['id'] }}" 
                                                {{ old('jabatan_id', $panitia->jabatan_id ?? '') == $pos['id'] ? 'selected' : '' }}>
                                                {{ $pos['nama'] }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
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