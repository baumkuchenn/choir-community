<div class="modal fade" id="editPanitiaModal" tabindex="-1" aria-labelledby="editPanitiaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPanitiaModalLabel">Edit Panitia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <div class="form-control" id="name" name="name">{{ $panitia->user->name }}</div>
                    </div>
                    <div class="col-6">
                        <label for="no_handphone" class="form-label">Nomor Handphone</label>
                        <div class="form-control" id="no_handphone" name="no_handphone">{{ $panitia->user->no_handphone }}</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <div class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                            {{ $panitia->user->jenis_kelamin_label }}    
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <div class="form-control" id="tanggal_lahir" name="tanggal_lahir">{{ $panitia->user->tanggal_lahir }}</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label for="email" class="form-label">Email</label>
                        <div class="form-control" id="email" name="email">{{ $panitia->user->email }}</div>
                    </div>
                    <div class="col-6">
                        <label for="kota" class="form-label">Asal Kota</label>
                        <div class="form-control" id="kota" name="kota">{{ $panitia->user->kota }}</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="alamat" class="form-label">Alamat</label>
                        <div class="form-control" id="alamat" name="alamat">{{ $panitia->user->alamat }}</div>
                    </div>
                </div>

                <form id="editForm" method="POST" action="{{ route('panitia.update', $panitia->id) }}" class="mb-0">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="jabatan_id" class="form-label">Jabatan Panitia</label>
                        <select class="form-select" name="jabatan_id" required>
                            <option value="">Tidak Ada</option>
                            @foreach($position as $divisi)
                                @if(!empty($divisi['jabatans']))
                                    <optgroup label="{{ $divisi['nama'] }}">
                                        @foreach($divisi['jabatans'] as $pos)
                                            <option value="{{ $pos['id'] }}" 
                                                {{ old('jabatan_id', $panitia->jabatan_id ?? '') == $pos['id'] ? 'selected' : '' }}>
                                                {{ $divisi['nama'] }} - {{ $pos['nama'] }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
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