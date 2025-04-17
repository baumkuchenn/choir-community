<div class="modal fade" id="addKuponModal" tabindex="-1" aria-labelledby="addKuponModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKuponModalLabel">Pilih Kupon / Masukkan Kode Referal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label>Pilih Kupon</label>
                    <select class="form-control" id="couponSelect">
                        @foreach($kupons as $item)
                            <option value="{{ $item->id }}" data-discount="{{ $item->potongan }}">
                                {{ $item->kode }} - Rp{{ number_format($item->potongan, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>Kode Referal</label>
                    <input type="text" class="form-control" id="referralInput">
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-primary mt-3 w-100" id="applyCoupon">Gunakan</button>
                </div>
            </div>
        </div>
    </div>
</div>