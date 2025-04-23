@extends('layouts.management')

@section('content')
<div class="container">
    <div class="col-md-11 col-lg-11 mx-auto">
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">Kembali</a>
        <h2 class="mb-3 fw-bold text-center">Tambah Anggota Komunitas Baru</h2>
        <form action="{{ route('members.store') }}" method="POST" class="mb-0">
            @csrf
            <div class="mb-3">
                <select class="form-control" id="user_id" name="user_id" required></select>
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