<div class="modal fade" id="editAnggotaModal" tabindex="-1" aria-labelledby="editAnggotaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAnggotaModalLabel">Edit Anggota Forum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <div class="form-control" id="name" name="name">{{ $member->user->name }}</div>
                    </div>
                </div>

                <form id="editForm" method="POST" action="{{ route('forum-member.update', $member->id) }}" class="mb-0">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <select class="form-select" name="jabatan" required>
                            <option value="moderator">Moderator</option>
                            <option value="anggota" selected>Anggota</option>
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