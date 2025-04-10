<div class="modal fade" id="editModal-ticket-type" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Jenis Tiket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="{{ route('ticket-types.update', $ticketType->id) }}" class="mb-0">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-ticket-id" name="id" value="{{ $ticketType->id }}">

                    <div class="mb-3">
                        <label for="edit-ticket-nama" class="form-label">Nama Tiket</label>
                        <input type="text" class="form-control" id="edit-ticket-nama" name="nama" value="{{ $ticketType->nama }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-ticket-harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="edit-ticket-harga" name="harga" value="{{ $ticketType->harga }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-ticket-jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="edit-ticket-jumlah" name="jumlah" value="{{ $ticketType->jumlah }}" required>
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