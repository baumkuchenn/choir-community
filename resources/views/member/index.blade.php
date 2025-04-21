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
        <h2 class="mb-3 fw-bold text-center">Daftar Anggota Komunitas</h2>
        <a href="{{ route('members.create') }}" class="btn btn-primary mb-3 fw-bold" >
            @if($choir->jenis_rekrutmen == 'seleksi')
                + Buka Seleksi
            @else
                + Tambah Anggota Baru
            @endif
        </a>
        <a href="{{ route('members.setting') }}" class="btn btn-outline-primary mb-3 fw-bold" >Pengaturan</a>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Pengurus</h5>
            </div>
            <table id="pengurusTable" class="table table-bordered shadow text-center">
                <thead>
                    <tr class="bg-primary">
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Divisi</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengurus as $item)
                        <tr>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->position->division->nama }}</td>
                            <td>{{ $item->position->nama }}</td>
                            <td class="d-flex justify-content-center gap-3">
                                <a href="{{ route('members.show', $item->id) }}" class="btn btn-primary">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Penyanyi</h5>
            </div>
            <table id="penyanyiTable" class="table table-bordered shadow text-center">
                <thead class="text-center">
                    <tr class="bg-primary">
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Suara</th>
                        <th class="text-center">Nomor Handphone</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penyanyi as $item)
                        <tr>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->suara_label }}</td>
                            <td>{{ $item->user->no_handphone }}</td>
                            <td class="d-flex justify-content-center gap-3">
                                <a href="{{ route('members.show', $item->id) }}" class="btn btn-primary">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#pengurusTable').DataTable({
            "lengthMenu": [5, 10, 20, 40],
            "language": {
                "emptyTable": "Belum ada pengurus dalam komunitas"
            }
        }); 
        $('#penyanyiTable').DataTable({
            "lengthMenu": [5, 10, 20, 40],
            "language": {
                "emptyTable": "Belum ada penyanyi dalam komunitas"
            }
        }); 
    });
</script>
@endsection