@extends('layouts.management')

@section('content')
<div class="container">
    @if(session('status') === 'success')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
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
                <thead class="text-center">
                    <tr class="bg-primary">
                        <th>Nama Lengkap</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($pengurus->isNotEmpty())
                        @foreach($pengurus as $member)
                            <tr>
                                <td>{{ $member->user->name }}</td>
                                <td>{{ $member->positions->divisions->nama }}</td>
                                <td>{{ $member->positions->nama }}</td>
                                <td class="d-flex justify-content-center gap-3">
                                    <form action="{{ route('members.show', $member->id) }}" method="POST" enctype="multipart/form-data" class="mb-0">
                                        @csrf
                                        <button class="btn btn-primary">Lihat Detail</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">Belum ada anggota pengurus</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Penyanyi</h5>
            </div>
            <table id="pengurusTable" class="table table-bordered shadow text-center">
                <thead class="text-center">
                    <tr class="bg-primary">
                        <th>Nama Lengkap</th>
                        <th>Suara</th>
                        <th>Nomor Handphone</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($penyanyi->isNotEmpty())
                        @foreach($penyanyi as $member)
                            <tr>
                                <td>{{ $member->user->name }}</td>
                                <td>{{ $member->suara }}</td>
                                <td>{{ $member->user->no_handphone }}</td>
                                <td class="d-flex justify-content-center gap-3">
                                    <form action="{{ route('members.show', $member->id) }}" method="POST" enctype="multipart/form-data" class="mb-0">
                                        @csrf
                                        <button class="btn btn-primary">Lihat Detail</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">Belum ada anggota penyanyi</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection