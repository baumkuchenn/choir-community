<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">Tambah Anggota Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('seleksi.tambah-pendaftar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="seleksis_id" id="seleksis_id" value="{{ $seleksi->id }}">
                    @if ($seleksi->tipe == 'member' || $seleksi->tipe == 'panitia')
                        <div class="mb-3 d-flex align-items-center gap-3">
                            <label><input type="radio" name="mode" value="ada" checked> Pilih Pengguna Terdaftar</label>
                            <label><input type="radio" name="mode" value="baru"> Tambah Email Baru</label>
                        </div>
                    @endif
                    <div class="mb-3" id="existing-user-section">
                        <label for="users_id" class="form-label">Pilih Pengguna</label>
                        <select class="form-control" id="users_id" name="users_id" required></select>
                    </div>
                    @if ($seleksi->tipe == 'member' || $seleksi->tipe == 'panitia')
                        <div class="mb-3" id="new-user-section" style="display: none;">
                            <label for="email" class="form-label">Email Pengguna Baru</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="contoh@email.com" disabled required>
                            <small class="text-muted">Pengguna akan menerima email untuk melengkapi data.</small>
                        </div>
                    @endif
                    @if ($seleksi->tipe == 'panitia')
                        <div class="mb-3">
                            <label for="tipe" class="form-label">Tipe Panitia</label>
                            <select class="form-select" id="tipe" name="tipe" required>
                                <option value="" disabled selected>Pilih Jenis</option>
                                <option value="internal">Internal</option>
                                <option value="eksternal">Eksternal</option>
                            </select>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>