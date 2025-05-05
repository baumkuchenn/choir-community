@extends('layouts.forum')

@section('content')
<div class="ps-3 pe-3 d-flex flex-nowrap">
    @include('forum.partials.sidebar', ['followForums' => $followForums, 'topForums' => $topForums])
    <div class="w-100">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="col-lg-11 mx-auto">
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ request('tab', 'posts') === 'posts' ? 'active' : '' }}" 
                    href="{{ route('forum.search', ['tab' => 'posts', 'search_input' => $keyword]) }}">
                    Posts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') === 'forums' ? 'active' : '' }}" 
                    href="{{ route('forum.search', ['tab' => 'forums', 'search_input' => $keyword]) }}">
                    Forums
                    </a>
                </li>
            </ul>
            <h5>Hasil pencarian untuk: "{{ $keyword }}"</h5>

            @if($tab === 'posts')
                @if($posts->count())
                    @foreach($posts as $item)
                        <a href="{{ route('posts.show', $item->id) }}" class="text-decoration-none text-body">
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ asset('storage/' . $item->forum->foto_profil) }}" alt="Profile" width="32" height="32" class="rounded-circle">
                                        <small class="text-muted"><span class="fw-bold">{{ $item->forum->nama }}</span> • {{ $item->created_at->diffForHumans() }}</small>
                                    </div>
                                    
                                    <p class="mt-2">{{ $item->isi }}</p>
                                    @foreach ($item->postAttachments as $attachment)
                                        <div class="attachment">
                                            @if (str_contains($attachment->file_type, 'image'))
                                                <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="Attachment" class="img-fluid" style="width:360px;">
                                            @elseif (str_contains($attachment->file_type, 'video'))
                                                <video controls>
                                                    <source src="{{ asset('storage/' . $attachment->file_path) }}" type="{{ $attachment->file_type }}">
                                                    Internet anda tidak bisa memulai video.
                                                </video>
                                            @endif
                                        </div>
                                    @endforeach
                                    <div class="d-flex gap-1 mt-1">
                                        <!-- Like Button -->
                                        <button class="btn btn-sm text-body react-btn" 
                                                data-id="{{ $item->id }}" 
                                                data-tipe="like">
                                            <i class="fa-{{ $item->userReaction && $item->userReaction->tipe === 'like' ? 'solid' : 'regular' }} fa-thumbs-up"></i>
                                            <span class="like-count">{{ $item->postReactions->where('tipe', 'like')->count() }}</span>
                                        </button>

                                        <!-- Dislike Button -->
                                        <button class="btn btn-sm text-body react-btn" 
                                                data-id="{{ $item->id }}" 
                                                data-tipe="dislike">
                                            <i class="fa-{{ $item->userReaction && $item->userReaction->tipe === 'dislike' ? 'solid' : 'regular' }} fa-thumbs-down"></i>
                                        </button>

                                        <!-- Comment Button -->
                                        <a href="{{ route('posts.show', $item->id) }}" class="btn btn-sm text-body">
                                            <i class="fa-regular fa-comment"></i>
                                            {{ $item->allRepliesCount() }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            @elseif($tab === 'forums')
                @foreach ($forums as $item)
                    <a href="{{ route('forum.show', $item->slug) }}" class="text-decoration-none text-body">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body d-flex align-items-center gap-3">
                                <img src="{{ asset('storage/' . $item->foto_profil) }}" alt="Profile" width="64" height="64" class="rounded-circle">
                                <div>
                                    <h5>{{ $item->nama }}</h5>
                                    <p class="mb-0 truncate-3-lines">{{ $item->deskripsi }}</p>
                                    <small class="text-muted">
                                        {{ $item->visibility_label }} • {{ $item->members->count() }} anggota
                                    </small>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif

            @if(!$forums->count() && !$posts->count())
                <p class="text-muted">Tidak ada hasil ditemukan.</p>
            @endif
        </div>
    </div>
</div>
@endsection