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
        <h3 class="my-3 fw-bold text-center">Pengaturan Divisi dan Jabatan {{ $event->nama }}</h3>
        <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-primary mb-3">Kembali</a>

        <form action="{{ route('panitia-divisi.ambil-kegiatan-lain', $event->id) }}" method="POST" class="mb-0">
            @csrf
            <div class="mb-3">
                <label for="event_lain_id" class="form-label">Ambil divisi dan jabatan dari kegiatan lain</label>
                <select class="form-select" id="event_lain_id" name="event_lain_id" required>
                    <option value="" disabled selected>Pilih Kegiatan</option>
                    @foreach ($events as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Divisi Panitia</h5>
            </div>
            <table id="divisiTable" class="table table-bordered shadow text-center">
                <thead>
                    <tr class="bg-primary">
                        <th class="text-center">Nama Singkatan</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($divisi as $item)
                        <tr>
                            <td>{{ $item->nama_singkat }}</td>
                            <td>{{ $item->nama }}</td>
                            <td class="d-flex justify-content-center gap-3">
                                <button class="btn btn-primary loadEditForm" data-action="{{ route('panitia-divisi.edit', ['divisi' => $item->id, 'event' => $event->id]) }}">Ubah</button>
                                <button class="btn btn-outline-danger deleteBtn" data-name="divisi {{ $item->nama }}" data-action="{{ route('panitia-divisi.destroy', $item->id) }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="btn btn-primary loadCreateForm fw-bold" data-action="{{ route('panitia-divisi.create', $event->id) }}">+ Tambah Divisi</button>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Jabatan Panitia</h5>
            </div>
            <table id="jabatanTable" class="table table-bordered shadow text-center">
                <thead class="text-center">
                    <tr class="bg-primary">
                        <th class="text-center">Nama</th>
                        <th class="text-center">Divisi</th>
                        <th class="text-center">Akses</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jabatan as $item)
                        <tr>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->divisi->nama }}</td>
                            <td>
                                @php
                                    $akses = [
                                        'akses_event' => 'Manajemen Kegiatan',
                                        'akses_eticket' => 'E-ticketing',
                                    ];
                                @endphp

                                @foreach($akses as $key => $label)
                                    @if($item->$key) 
                                        <span class="badge bg-secondary">{{ $label }}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td class="d-flex justify-content-center gap-3">
                                <button class="btn btn-primary loadEditForm" data-action="{{ route('panitia-jabatan.edit', ['jabatan' => $item->id, 'event' => $event->id]) }}">Ubah</button>
                                <button class="btn btn-outline-danger deleteBtn" data-name="jabatan {{ $item->nama }}" data-action="{{ route('panitia-jabatan.destroy', $item->id) }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="btn btn-primary loadCreateForm fw-bold" data-action="{{ route('panitia-jabatan.create', $event->id) }}">+ Tambah Jabatan</button>
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
        $('#jabatanTable').DataTable({
            "lengthMenu": [5, 10, 20, 40],
            "order": [[1, "asc"]],
            "language": {
                "emptyTable": "Belum ada divisi pengurus komunitas"
            }
        });

        $('#divisiTable').DataTable({
            "lengthMenu": [5, 10, 20, 40],
            "language": {
                "emptyTable": "Belum ada jabatan per divisi pengurus"
            }
        });
        
    });
</script>
@endsection