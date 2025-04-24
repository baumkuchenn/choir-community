<div class="modal fade" id="createTicketInviteModal" tabindex="-1" aria-labelledby="createTicketInviteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTicketInviteModalLabel">Tambah Tamu Undangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('ticket-invites.store') }}" method="POST" class="mb-0">
                    @csrf
                    <input type="hidden" id="concerts_id" name="concerts_id" value="">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Penerima</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_handphone" class="form-label">Nomor Handphone</label>
                        <input type="text" class="form-control" id="no_handphone" name="no_handphone" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="ticket_types_id" class="form-label">Jenis Tiket</label>
                        <select class="form-control" id="ticket_types_id" name="ticket_types_id">
                            @foreach($ticketTypes as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah Tiket</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
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