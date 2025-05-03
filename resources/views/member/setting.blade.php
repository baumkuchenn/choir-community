@extends('layouts.management')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="col-md-11 col-lg-11 mx-auto">
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">Kembali</a>
        <h2 class="mb-3 fw-bold text-center">Daftar Anggota Komunitas</h2>
        <h5 class="mb-3 fw-bold">Pengaturan</h5>
        
        <form action="{{ route('choir.update', $choir->id) }}" method="POST" class="row mb-3">
            @csrf
            @method('PUT')
            <div class="col-12 mb-3">
                <label for="nama" class="form-label">Metode Perekrutan Anggota</label>
                <select name="jenis_rekrutmen" class="form-select">
                    <option value="invite" {{ old('jenis_rekrutmen', $choir->jenis_rekrutmen ?? '') == 'invite' ? 'selected' : '' }}>Hanya Undangan</option>
                    <option value="seleksi" {{ old('jenis_rekrutmen', $choir->jenis_rekrutmen ?? '') == 'seleksi' ? 'selected' : '' }}>Seleksi</option>
                </select>
            </div>
            @can('akses-admin')
                <div class="col-2">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            @endcan
        </form>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Butir Penilaian Seleksi</h5>
            </div>
            <table id="butirTable" class="table table-bordered shadow text-center">
                <thead class="text-center">
                    <tr class="bg-primary">
                        <th>Nama</th>
                        <th>Bobot Nilai (%)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($butirPenilaian as $item)
                        <tr>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->bobot_nilai }}</td>
                            <td class="d-flex justify-content-center gap-3">
                                <button class="btn btn-primary loadEditForm" data-action="{{ route('butir-penilaian.edit', $item->id) }}">Ubah</button>
                                <button class="btn btn-outline-danger deleteBtn" data-name="jabatan {{ $item->nama }}" data-action="{{ route('butir-penilaian.destroy', $item->id) }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="btn btn-primary loadCreateForm fw-bold" data-action="{{ route('butir-penilaian.create') }}">+ Tambah Butir Penilaian</button>
        </div>

        <div id="modalContainer"></div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".loadCreateForm").forEach(button => {
            button.addEventListener("click", function() {
                let route = this.dataset.action;
                fetch(route)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('modalContainer').innerHTML = html;
                        new bootstrap.Modal(document.getElementById('createModal')).show();
                    });
            });
        });

        document.querySelectorAll(".loadEditForm").forEach((button) => {
            button.addEventListener("click", function() {
                let route = this.dataset.action;
                fetch(route)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById("modalContainer").innerHTML = html;
                        new bootstrap.Modal(document.getElementById('editModal')).show();
                    })
                    .catch(error => console.error("Error loading modal:", error));
            });
        });

        //Data table untuk search bar
        $('#butirTable').DataTable({
            "language": {
                "emptyTable": "Belum ada butir penilaian seleksi anggota baru"
            }
        });
        
    });
</script>
@endsection