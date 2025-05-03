<div class="d-none d-lg-flex flex-column flex-shrink-0 p-3 min-vh-100 border-end" style="width: 20%;">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('forum.index') }}" class="nav-link {{ request()->routeIs('forum.index') ? 'active' : 'text-body' }}">
                <i class="fas fa-home fa-fw me-2"></i>
                Home
            </a>
        </li>
        @if(Auth::user())
            <li class="mt-2">
                <a href="{{ route('forum.create') }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-plus-circle me-1"></i>
                    Buat Forum Baru
                </a>
            </li>
            <hr>
            <small>Forum yang diikuti</small>
            @foreach($followForums as $item)
                <li>
                    <a href="{{ route('forum.show', $item->slug) }}" class="nav-link text-body d-flex align-items-center gap-2">
                        <img src="{{ asset('storage/' . $item->foto_profil) }}" alt="Profile" width="32" height="32" class="rounded-circle">
                        <span class="text-truncate" style="max-width: 150px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $item->nama }}
                        </span>
                    </a>
                </li>
            @endforeach
        @endif
        <hr>
        <small>Forum populer</small>
        @foreach($topForums as $item)
            <li>
                <a href="{{ route('forum.show', $item->slug) }}" class="nav-link text-body d-flex align-items-center gap-2">
                    <img src="{{ asset('storage/' . $item->foto_profil) }}" alt="Profile" width="32" height="32" class="rounded-circle">
                    <span class="text-truncate" style="max-width: 150px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $item->nama }}
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</div>