<div class="modal fade" id="editTicketModal" tabindex="-1" aria-labelledby="editTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTicketModalLabel">Edit Jenis Tiket</h5>
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

                    <div class="mb-3">
                        <label for="pembelian_terakhir" class="form-label">Pembelian Terakhir</label>
                        <input type="datetime-local" class="form-control" id="pembelian_terakhir" name="pembelian_terakhir" value="{{ $ticketType->pembelian_terakhir }}" max="{{ \Carbon\Carbon::parse($ticketType->concert->event->tanggal_mulai)->format('Y-m-d\TH:i') }}" required>
                    </div>

                    @can('akses-admin')
                        <div class="mb-3">
                            <label for="visibility" class="form-label">Ketersediaan</label>
                            <select class="form-select" id="visibility" name="visibility" required>
                                <option value="public" {{ $ticketType->visibility == 'public' ? 'selected' : '' }}>Dijual</option>
                                <option value="private" {{ $ticketType->visibility == 'private' ? 'selected' : '' }}>Tidak Dijual</option>
                            </select>
                        </div>
                    @endcan

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>