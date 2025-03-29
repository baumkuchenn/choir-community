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
        <h2 class="mb-3 fw-bold text-center">Daftar Jabatan Pengurus Komunitas</h2>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Divisi Pengurus</h5>
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
                                <button class="btn btn-primary loadEditForm" data-action="{{ route('divisions.edit', $item->id) }}">Ubah</button>
                                <button class="btn btn-outline-danger deleteBtn" data-name="divisi {{ $item->nama }}" data-action="{{ route('divisions.destroy', $item->id) }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="btn btn-primary loadCreateForm fw-bold" data-action="{{ route('divisions.create') }}">+ Tambah Divisi</button>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Jabatan Pengurus</h5>
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
                            <td>{{ $item->division->nama }}</td>
                            <td>
                                @php
                                    $akses = [
                                        'akses_member' => 'Manajemen Anggota',
                                        'akses_roles' => 'Manajemen Jabatan',
                                        'akses_event' => 'Manajemen Kegiatan',
                                        'akses_eticket' => 'E-ticketing',
                                        'akses_forum' => 'Forum',
                                    ];
                                @endphp

                                @foreach($akses as $key => $label)
                                    @if($item->$key) 
                                        <span class="badge bg-secondary">{{ $label }}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td class="d-flex justify-content-center gap-3">
                                <button class="btn btn-primary loadEditForm" data-action="{{ route('positions.edit', $item->id) }}">Ubah</button>
                                <button class="btn btn-outline-danger deleteBtn" data-name="jabatan {{ $item->nama }}" data-action="{{ route('positions.destroy', $item->id) }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="btn btn-primary loadCreateForm fw-bold" data-action="{{ route('positions.create') }}">+ Tambah Jabatan</button>
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