<div class="modal fade" id="createKuponModal" tabindex="-1" aria-labelledby="createKuponModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createKuponModalLabel">Tambah Kupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kupon.store') }}" method="POST" class="mb-0">
                    @csrf
                    <input type="hidden" id="concerts_id" name="concerts_id" value="">
                    <input type="hidden" name="tipe" value="kupon">
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode Kupon</label>
                        <input type="text" class="form-control" id="kode" name="kode" required>
                    </div>
                    <div class="mb-3">
                        <label for="potongan" class="form-label">Potongan Harga</label>
                        <input type="number" class="form-control" id="potongan" name="potongan" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah Kupon</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="waktu_expired" class="form-label">Waktu Kadaluwarsa</label>
                        <input type="datetime-local" class="form-control" id="waktu_expired" name="waktu_expired" max="{{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('Y-m-d\TH:i') }}" required>
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