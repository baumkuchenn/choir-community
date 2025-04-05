@if ($choir->isEmpty())
    <p class="text-center">Belum ada komunitas yang membuka pendaftaran anggota baru.</p>
@else
    @foreach ($choir as $item)
        <div class="card shadow border-0 mb-3">
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="mt-2 d-flex align-items-center gap-3">
                            <img src="{{ asset('storage/'. $item->logo) }}" style="width: 60px; height: 60px">
                            <div>
                                <h4 class="mb-0">{{ $item->nama }}</h4>
                                <p class="mb-0">{{ $item->kota }}</p>
                            </div>
                        </div>
                        <div class="col-md-5 text-end d-none d-md-block">
                            <a href="join/detail/{{ $item->seleksis->first()->id }}" class="btn btn-primary">Gabung</a>
                        </div>
                    </div>
                    <div class="col-12 text-end d-block d-md-none">
                        <a href="join/detail/{{ $item->seleksis->first()->id }}" class="btn btn-primary">Gabung</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Pagination -->
    {{ $choir->links() }}
@endif