<div class="modal fade" id="createReferalModal" tabindex="-1" aria-labelledby="createReferalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createReferalModalLabel">Tambah Kupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kupon.store') }}" method="POST" class="mb-0">
                    @csrf
                    <input type="hidden" id="concerts_id" name="concerts_id" value="">
                    <input type="hidden" name="tipe" value="referal">
                    <div class="mb-3">
                        <div class="d-flex justify-content-start mb-2">
                            <button type="button" id="pilihSemuaBtn" class="btn btn-sm btn-secondary">Pilih Semua</button>
                        </div>
                        <table id="penyanyiReferalTable" class="table table-bordered shadow text-center">
                            <thead>
                                <tr class="bg-primary">
                                    <th class="text-center">Pilih</th>
                                    <th class="text-center">Nama Lengkap</th>
                                    <th class="text-center">Nomor Handphone</th>
                                    <th class="text-center">Jenis Kelamin</th>
                                    <th class="text-center">Suara</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penyanyi as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox-penyanyi" name="members_id[]" value="{{ $item->member->id }}">
                                        </td>
                                        <td>{{ $item->member->user->name }}</td>
                                        <td>{{ $item->member->user->no_handphone }}</td>
                                        <td>{{ $item->member->user->jenis_kelamin }}</td>
                                        <td>{{ $item->suara_label }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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