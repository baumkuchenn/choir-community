@extends('layouts.management')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="col-md-11 col-lg-11 mx-auto">
        <h3 class="mb-3 fw-bold text-center">Data Pendaftar Seleksi</h3>
        <h5 class="fw-medium text-center">
            @if ($seleksi->tanggal_mulai != $seleksi->tanggal_selesai)
                {{ \Carbon\Carbon::parse($seleksi->tanggal_mulai)->translatedFormat('d') }} - 
                {{ \Carbon\Carbon::parse($seleksi->tanggal_selesai)->translatedFormat('d F Y') }}
            @else
                {{ \Carbon\Carbon::parse($seleksi->tanggal_mulai)->translatedFormat('d F Y') }}
            @endif
        </h5>
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary mb-3">Kembali</a>
        <div class="row mb-3">
            <div class="col-12 d-flex gap-3 align-items-center">
                <h5 class="fw-bold">Status Kehadiran
                    @if ($pendaftar->kehadiran == 'belum')
                        <span class="text-primary">-</span>
                    @elseif ($pendaftar->kehadiran == 'ya')
                        <span class="text-primary">Hadir</span>
                    @elseif ($pendaftar->kehadiran == 'tidak')
                        <span class="text-primary">Tidak Hadir</span>
                    @endif
                </h5>
                @if ($pendaftar->kehadiran == 'belum')
                    <form action="{{ route('seleksi.checkin-pendaftar') }}" method="POST" enctype="multipart/form-data" class="mb-0">
                        @csrf
                        <input type="hidden" name="seleksis_id" value="{{$seleksi->id}}">
                        <input type="hidden" name="users_id" value="{{$pendaftar->users_id}}">
                        <input type="hidden" name="kehadiran" value="ya">
                        <button class="btn btn-primary">Hadir</button>
                    </form>
                    <form action="{{ route('seleksi.checkin-pendaftar') }}" method="POST" enctype="multipart/form-data" class="mb-0">
                        @csrf
                        <input type="hidden" name="seleksis_id" value="{{$seleksi->id}}">
                        <input type="hidden" name="users_id" value="{{$pendaftar->users_id}}">
                        <input type="hidden" name="kehadiran" value="tidak">
                        <button class="btn btn-outline-primary">Tidak</button>
                    </form>
                @endif
            </div>
        </div>
        <form method="POST" action="{{ route('seleksi.simpan-pendaftar') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="seleksis_id" value="{{$seleksi->id}}">
            <input type="hidden" name="users_id" value="{{$pendaftar->users_id}}">
            
            <div class="row mb-3">
                <div class="col-6">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <div class="form-control" id="name" name="name">{{ $pendaftar->user->name }}</div>
                </div>
                <div class="col-6">
                    <label for="no_handphone" class="form-label">Nomor Handphone</label>
                    <div class="form-control" id="no_handphone" name="no_handphone">{{ $pendaftar->user->no_handphone }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <div class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                        @if($pendaftar->user->jenis_kelamin == 'L')
                            Laki-laki
                        @else
                            Perempuan
                        @endif
                    </div>
                </div>
                <div class="col-6">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <div class="form-control" id="tanggal_lahir" name="tanggal_lahir">{{ $pendaftar->user->tanggal_lahir }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="email" class="form-label">Email</label>
                    <div class="form-control" id="email" name="email">{{ $pendaftar->user->email }}</div>
                </div>
                <div class="col-6">
                    <label for="kota" class="form-label">Asal Kota</label>
                    <div class="form-control" id="kota" name="kota">{{ $pendaftar->user->kota }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="alamat" class="form-label">Alamat</label>
                    <div class="form-control" id="alamat" name="alamat">{{ $pendaftar->user->alamat }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <h5 class="fw-bold">Catatan Wawancara</h5>
                    <textarea class="form-control" name="hasil_wawancara" id="hasil_wawancara" rows="10" required>{{ old('hasil_wawancara', $pendaftar->hasil_wawancara) }}</textarea>
                </div>
            </div>

            @php
                $choir = \App\Models\Choir::find($seleksi->choirs_id);
            @endphp
            <div class="row">
                <h5 class="fw-bold">Hasil Penilaian Audisi</h5>
                <div class="row mb-3">
                    <div class="col-6">
                        <label for="range_suara" class="form-label">Range Suara</label>
                        <input type="text" name="range_suara" id="range_suara" class="form-control" value="{{ old('range_suara', $pendaftar->range_suara) }}" required>
                    </div>
                    <div class="col-6">
                        <label for="kategori_suara" class="form-label">Kategori Suara</label>
                        <select class="form-select" id="kategori_suara" name="kategori_suara" required>
                            <option value="" disabled>Pilih Suara</option>
                            @if($choir->tipe == 'SSAA' || $choir->tipe == 'SSAATTBB')
                                <option value="sopran_1" {{ old('kategori_suara', $pendaftar->kategori_suara) == 'sopran_1' ? 'selected' : '' }}>Sopran 1</option>
                                <option value="sopran_2" {{ old('kategori_suara', $pendaftar->kategori_suara) == 'sopran_2' ? 'selected' : '' }}>Sopran 2</option>
                                <option value="alto_1" {{ old('kategori_suara', $pendaftar->kategori_suara) == 'alto_1' ? 'selected' : '' }}>Alto 1</option>
                                <option value="alto_2" {{ old('kategori_suara', $pendaftar->kategori_suara) == 'alto_2' ? 'selected' : '' }}>Alto 2</option>
                            @endif
                            @if($choir->tipe == 'TTBB' || $choir->tipe == 'SSAATTBB')
                                <option value="tenor_1" {{ old('kategori_suara', $pendaftar->kategori_suara) == 'tenor_1' ? 'selected' : '' }}>Tenor 1</option>
                                <option value="tenor_2" {{ old('kategori_suara', $pendaftar->kategori_suara) == 'tenor_2' ? 'selected' : '' }}>Tenor 2</option>
                                <option value="bass_1" {{ old('kategori_suara', $pendaftar->kategori_suara) == 'bass_1' ? 'selected' : '' }}>Bass 1</option>
                                <option value="bass_2" {{ old('kategori_suara', $pendaftar->kategori_suara) == 'bass_2' ? 'selected' : '' }}>Bass 2</option>
                            @endif
                        </select>
                    </div>
                </div>
                @foreach($butir as $item)
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="nilai_{{ $item->id }}" class="form-label">{{ $item->nama }} ({{ $item->bobot_nilai }}%)</label>
                            <input type="hidden" name="butir_penilaians[{{ $item->id }}][id]" value="{{ $item->id }}">
                            <input type="hidden" name="butir_penilaians[{{ $item->id }}][bobot_nilai]" value="{{ $item->bobot_nilai }}">
                            <input type="text" name="butir_penilaians[{{ $item->id }}][nilai]" id="nilai_{{ $item->id }}" class="form-control"
                                   value="{{ old('butir_penilaians.' . $item->id . '.nilai', $pendaftar->nilais->where('id', $item->id)->first()?->pivot->nilai / ($item->bobot_nilai / 100)) }}" required>
                        </div>
                    </div>
                @endforeach
                <div class="mb-3">
                    <label class="form-label fw-bold">Upload Lembar Penilaian</label>
                    <div class="border p-3 rounded text-center" id="upload-box" style="border: 2px dashed #ccc;">
                        <input type="file" id="lembarPenilaian" name="lembar_penilaian" class="d-none" accept="image/*,.pdf">
                        @if ($pendaftar->nilais->isEmpty())
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('lembarPenilaian').click();">
                                Pilih File
                            </button>
                        @endif
                        <p class="text-muted mt-2">Hanya file JPG, PNG, dan PDF. Maksimal 2MB.</p>
                        <div id="preview-container" class="mt-3">
                            @if(old('lembar_penilaian', $pendaftar->lembar_penilaian ?? false))
                                @php
                                    $filePath = asset('storage/' . old('lembar_penilaian', $pendaftar->lembar_penilaian));
                                    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                @endphp

                                @if(in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                    <img src="{{ $filePath }}" style="width: 150px; border-radius: 10px;" class="mt-2">
                                @elseif($fileExtension === 'pdf')
                                    <div>
                                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                        <p><a href="{{ $filePath }}" target="_blank">Lihat PDF</a></p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @if ($pendaftar->lolos == 'belum')
                    @if ($pendaftar->nilais->isNotEmpty())
                        <div class="col-6 d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-primary w-75 fw-bold" onclick="savePendaftar('tidak')">Tolak</button>
                        </div>
                        <div class="col-6 d-flex justify-content-center">
                            <button type="button" class="btn btn-primary w-75 fw-bold" onclick="savePendaftar('ya')">Terima</button>
                        </div>
                    @else
                        <div class="col-12 d-flex justify-content-center">
                            <button class="btn btn-primary w-75 fw-bold">Simpan Hasil Seleksi</button>
                        </div>
                    @endif
                @endif
            </div>
        </form>
        <form id="lolos-form" method="POST" action="{{ route('seleksi.lolos-pendaftar') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="is_lolos" id="is_lolos" value="">
            <input type="hidden" name="seleksis_id" value="{{$seleksi->id}}">
            <input type="hidden" name="users_id" value="{{$pendaftar->users_id}}">
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    function savePendaftar(value){
            document.getElementById('is_lolos').value = value;
            document.getElementById("lolos-form").submit();
    };

    document.addEventListener("DOMContentLoaded", function(){
        document.getElementById('lembarPenilaian').addEventListener('change', function (event) {
            let file = event.target.files[0];
            let previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = ''; // Clear previous preview

            if (file) {
                if (file.type.startsWith('image/')) {
                    let img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.width = '150px';
                    img.style.borderRadius = '10px';
                    img.classList.add('mt-2');
                    previewContainer.appendChild(img);
                } else if (file.type === 'application/pdf') {
                    let pdfIcon = document.createElement('div');
                    pdfIcon.innerHTML = `<i class="fas fa-file-pdf fa-3x text-danger"></i>
                                            <p>${file.name}</p>`;
                    previewContainer.appendChild(pdfIcon);
                }
            }
        });
    });
</script>
@endsection