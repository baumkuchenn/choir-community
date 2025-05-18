@extends('layouts.management')

@section('content')
<div class="container">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="col-md-11 col-lg-11 mx-auto">
        <a href="{{ route('members.index') }}" class="btn btn-outline-primary">Kembali</a>
        <h2 class="mb-3 fw-bold text-center">Tambah Anggota Komunitas Baru</h2>
        <form action="{{ route('members.store') }}" method="POST" class="mb-0">
            @csrf
            <div class="mb-3">
                <label for="user_id" class="form-label">Nama Pengguna</label>
                <select class="form-control" id="user_id" name="user_id" required></select>
            </div>
            <div class="mb-3">
                <label for="suara" class="form-label">Kategori Suara</label>
                <select class="form-select" id="suara" name="suara" required>
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
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#user_id').select2({
            placeholder: 'Cari Pengguna...',
            width: '100%',
            ajax: {
                url: '{{ route("members.search") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            }
        });
    });
</script>
@endsection