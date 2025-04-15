<div class="modal fade" id="createTicketModal" tabindex="-1" aria-labelledby="createTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTicketModalLabel">Tambah Jenis Tiket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('ticket-types.store') }}" method="POST" class="mb-0">
                    @csrf
                    <input type="hidden" id="concerts_id" name="concerts_id" value="">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Tiket</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="pembelian_terakhir" class="form-label">Pembelian Terakhir</label>
                        <input type="datetime-local" class="form-control" id="pembelian_terakhir" name="pembelian_terakhir" required>
                    </div>
                    @can('akses-admin')
                    <div class="mb-3">
                        <label for="visibility" class="form-label">Ketersediaan</label>
                        <select class="form-select" id="visibility" name="visibility" required>
                            <option value="public">Dijual</option>
                            <option value="private">Tidak Dijual</option>
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