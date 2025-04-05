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
        <h2 class="mb-3 fw-bold text-center">Daftar Kegiatan Komunitas</h2>
        @can('akses-event')
            <a href="{{ route('events.create') }}" class="btn btn-primary mb-3 fw-bold" >+ Tambah Kegiatan</a>
        @endcan

        <div class="d-flex justify-content-between align-items-end mt-4 mb-3">
            <h5 class="fw-bold">Kegiatan Kedepannya</h5>
            <input type="text" id="eventSelanjutnyaSearch" class="form-control w-25" placeholder="Search..." autocomplete="off">
        </div>
        <hr>
        <div id="eventSelanjutnyaLoading" class="text-center mt-2" style="display: none;">
            <span class="spinner-border text-primary" role="status"></span>
        </div>
        <div id="eventSelanjutnyaList"> 
            @include('event.partials.event_selanjutnya_list', ['eventSelanjutnya' => $eventSelanjutnya])
        </div>
        

        <div class="d-flex justify-content-between align-items-end mt-4 mb-3">
            <h5 class="fw-bold">Kegiatan Lalu</h5>
            <input type="text" id="eventLaluSearch" class="form-control w-25" placeholder="Search..." autocomplete="off">
        </div>
        <hr>
        <div id="eventLaluLoading" class="text-center mt-2" style="display: none;">
            <span class="spinner-border text-primary" role="status"></span>
        </div>
        <div id="eventLaluList"> 
            @include('event.partials.event_lalu_list', ['eventLalu' => $eventLalu])
        </div>
    </div>
</div>
@endsection

@section ('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function fetchEventSelanjutnya(page = 1, search = '') {
            document.getElementById('eventSelanjutnyaLoading').style.display = 'block'; // Show loading animation

            fetch(`{{ route('events.search.selanjutnya') }}?search=${search}&page=${page}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('eventSelanjutnyaList').innerHTML = data;
                    document.getElementById('eventSelanjutnyaLoading').style.display = 'none'; // Hide loading animation
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        function fetchEventLalu(page = 1, search = '') {
            document.getElementById('eventLaluLoading').style.display = 'block'; // Show loading animation

            fetch(`{{ route('events.search.lalu') }}?search=${search}&page=${page}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('eventLaluList').innerHTML = data;
                    document.getElementById('eventLaluLoading').style.display = 'none'; // Hide loading animation
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        // Search function
        document.getElementById('eventSelanjutnyaSearch').addEventListener('keyup', function () {
            let search = this.value;
            fetchEventLalu(1, search);
        });
        document.getElementById('eventLaluSearch').addEventListener('keyup', function () {
            let search = this.value;
            fetchEventLalu(1, search);
        });

        // Handle pagination click
        document.addEventListener('click', function (e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                let page = new URL(e.target.href).searchParams.get("page");
                if (link.closest('#eventSelanjutnyaList')) {
                    const search = document.getElementById('eventSelanjutnyaSearch').value;
                    fetchEventSelanjutnya(page, search);
                }

                if (link.closest('#eventLaluList')) {
                    const search = document.getElementById('eventLaluSearch').value;
                    fetchEventLalu(page, search);
                }
            }
        });
    });
</script>
@endsection