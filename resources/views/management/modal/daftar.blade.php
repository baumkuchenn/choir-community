<div class="modal fade" id="daftarConfirmModal" tabindex="-1" aria-labelledby="daftarConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="daftarConfirmModalLabel">Konfirmasi Daftar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4 text-center">
                    <h5 class="fw-bold mb-2" id="modalEventNama">Nama Kegiatan</h5>
                    <div class=" small">
                        <i class="bi bi-calendar-event me-1"></i> <span id="modalEventTanggal">Tanggal</span><br>
                        <i class="bi bi-geo-alt me-1"></i> <span id="modalEventLokasi">Lokasi</span>
                    </div>
                </div>
                <p>Apakah Anda ingin mendaftar kegiatan <span id="modalEventNama"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                <form id="daftarForm" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Ya</button>
                </form>
            </div>
        </div>
    </div>
</div>