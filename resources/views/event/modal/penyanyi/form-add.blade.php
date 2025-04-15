<div class="modal fade" id="tambahPenyanyiModal" tabindex="-1" aria-labelledby="tambahPenyanyiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPenyanyiModalLabel">Tambah Penyanyi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('penyanyi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="events_id" name="events_id" value="">
                    <div class="mb-3 d-flex align-items-center gap-3">
                        <label><input type="radio" name="mode" value="seleksi" checked> Hasil Seleksi</label>
                        <label><input type="radio" name="mode" value="baru"> Penyanyi Baru</label>
                        <label><input type="radio" name="mode" value="event"> Kegiatan Lain</label>
                    </div>
                    <div id="penyanyi-seleksi-section">
                        <div class="d-flex justify-content-start mb-2">
                            <button type="button" id="pilihSemuaBtn" class="btn btn-sm btn-secondary">Pilih Semua</button>
                        </div>
                        <table id="seleksiTable" class="table table-bordered shadow text-center">
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
                                @foreach($pendaftar as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox-penyanyi" name="members_id[]" value="{{ $item->user->members->first()->id }}">
                                        </td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->no_handphone }}</td>
                                        <td>{{ $item->user->jenis_kelamin }}</td>
                                        <td>
                                            @if($item->kategori_suara == 'sopran_1')
                                                Sopran 1
                                            @elseif($item->kategori_suara == 'sopran_2')
                                                Sopran 2
                                            @elseif($item->kategori_suara == 'alto_1')
                                                Alto 1
                                            @elseif($item->kategori_suara == 'alto_2')
                                                Alto 2
                                            @elseif($item->kategori_suara == 'tenor_1')
                                                Tenor 1
                                            @elseif($item->kategori_suara == 'tenor_2')
                                                Tenor 2
                                            @elseif($item->kategori_suara == 'bass_1')
                                                Bass 1
                                            @elseif($item->kategori_suara == 'bass_2')
                                                Bass 2
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="penyanyi-baru-section" style="display:none">
                        <div class="mb-3">
                            <label for="members_id" class="form-label">Pilih Anggota</label>
                            <select class="form-control" id="members_id" name="members_id" required disabled></select>
                        </div>
                        <div class="mb-3">
                            <label for="suara" class="form-label">Kategori Suara</label>
                            <select class="form-select" id="suara" name="suara" required disabled>
                                <option value="" disabled>Pilih Suara</option>
                                @if($choir->tipe == 'SSAA' || $choir->tipe == 'SSAATTBB')
                                    <option value="sopran_1" {{ old('suara') == 'sopran_1' ? 'selected' : '' }}>Sopran 1</option>
                                    <option value="sopran_2" {{ old('suara') == 'sopran_2' ? 'selected' : '' }}>Sopran 2</option>
                                    <option value="alto_1" {{ old('suara') == 'alto_1' ? 'selected' : '' }}>Alto 1</option>
                                    <option value="alto_2" {{ old('suara') == 'alto_2' ? 'selected' : '' }}>Alto 2</option>
                                @endif
                                @if($choir->tipe == 'TTBB' || $choir->tipe == 'SSAATTBB')
                                    <option value="tenor_1" {{ old('suara') == 'tenor_1' ? 'selected' : '' }}>Tenor 1</option>
                                    <option value="tenor_2" {{ old('suara') == 'tenor_2' ? 'selected' : '' }}>Tenor 2</option>
                                    <option value="bass_1" {{ old('suara') == 'bass_1' ? 'selected' : '' }}>Bass 1</option>
                                    <option value="bass_2" {{ old('suara') == 'bass_2' ? 'selected' : '' }}>Bass 2</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div id="event-lain-section" style="display: none;">
                        <div class="mb-3">
                            <label for="event_lain_id" class="form-label">Ambil Dari Kegiatan</label>
                            <select class="form-select" id="event_lain_id" name="event_lain_id" required disabled>
                                <option value="" disabled selected>Pilih Kegiatan</option>
                                @foreach ($events as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>