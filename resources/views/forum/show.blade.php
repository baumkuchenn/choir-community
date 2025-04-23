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
        <div class="col-lg-11 mx-auto position-relative">
            <div class="position-relative">
                <img src="{{ asset('storage/' . $forum->foto_banner) }}" alt="Banner" class="w-100 rounded" style="height: 180px; object-fit: cover;">
            </div>

            <div class="d-flex flex-wrap align-items-center justify-content-between bg-white p-3 rounded-bottom shadow-sm mb-3" style="margin-top: -20px;">
                <div style="position: absolute; top: 140px; left: 20px;">
                    <img src="{{ asset('storage/' . $forum->foto_profil) }}" alt="Profile" width="96" height="96" class="rounded-circle border border-white shadow">
                </div>
                <div class="mt-2" style="margin-left: 110px;">
                    <h4 class="mb-0">{{ $forum->nama }}</h4>
                    <small class="text-muted">
                        {{ $forum->visibility_label }} • {{ $forum->members->count() }} anggota
                    </small>
                </div>
                @if(Auth::user())
                    <div class="mt-2">
                        @if($isMember)
                            <div class="d-flex gap-2">
                                @if(in_array($jabatan, ['admin', 'moderator']))
                                    <button type="button" class="btn btn-outline-primary fw-bold create-modal" data-id="{{ $forum->id }}" data-name="topik" data-action="{{ route('topik.index', $forum->slug) }}">Tambah Topik</button>
                                    <div id="modalContainer"></div>
                                @endif
                                <div class="dropdown">
                                    <button class="btn btn-light border dropdown-toggle p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-bars"></i> {{-- Font Awesome hamburger icon --}}
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                                        <li><a class="dropdown-item" href="{{ route('forum.pengaturan', $forum->slug) }}">Pengaturan</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button type="button" class="dropdown-item text-danger btn-keluar" data-action="{{ route('forum.keluar', $forum->slug) }}">Keluar</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @else
                            <form method="POST" action="{{ route('forum.masuk', $forum->slug) }}" class="mb-0">
                                @csrf
                                <button type="submit" class="btn btn-primary fw-medium">Gabung</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            @if($isMember)
                <form method="POST" action="{{ route('posts.store', $forum->slug) }}" enctype="multipart/form-data" class="card border-0 shadow-sm mb-3">
                    @csrf
                    <input type="hidden" name="tipe" value="post">
                    <div class="card-body d-flex">
                        <!-- User Avatar -->
                        <img src="https://github.com/mdo.png" alt="Profile" width="32" height="32" class="rounded-circle">

                        <!-- Post Input Area -->
                        <div class="ps-2 flex-grow-1">
                            <!-- Select Topic -->
                            <select name="topics_id" class="form-select mb-2" required>
                                <option value="" disabled selected>Pilih Topik</option>
                                @foreach($forum->topics as $topic)
                                    <option value="{{ $topic->id }}">{{ $topic->nama }}</option>
                                @endforeach
                            </select>

                            <!-- Post Content -->
                            <textarea name="isi" class="form-control border-0" rows="3" placeholder="Apa yang anda pikirkan?" style="resize: none;" required></textarea>

                            <!-- File Upload -->
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="d-flex gap-2">
                                    <label class="btn btn-sm btn-outline-secondary mb-0">
                                        <i class="fa fa-image"></i> Upload foto/video
                                        <input type="file" name="media[]" class="d-none" multiple accept="image/*,video/*" onchange="previewMedia(event)">
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-md px-4 fw-bold">Unggah</button>
                            </div>

                            <!-- Media Preview -->
                            <div id="media-preview" class="mt-2" style="display: none;">
                                <img id="preview-image" src="#" alt="Preview" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                </form>
            @endif

            @foreach($posts as $post)
                <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-body">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <img src="{{ asset('storage/' . $post->forum->foto_profil) }}" alt="Profile" width="32" height="32" class="rounded-circle">
                                <div>
                                    <p class="mb-0 text-muted small"><span class="fw-bold">{{ $post->forum->nama }}</span> • {{ $post->created_at->diffForHumans() }}</p>
                                    <p class="text-muted mb-0 small">oleh {{ $post->creator->name }}</p>
                                </div>
                            </div>
                            <h6 class="mb-2">Topik:
                                <a href="#">{{ $post->topic->nama }}</a>
                            </h6>
                            <p class="mb-2">{{ $post->isi }}</p>
                            @foreach ($post->postAttachments as $attachment)
                                <div class="attachment">
                                    @if (str_contains($attachment->file_type, 'image'))
                                        <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="Attachment" class="img-fluid" style="width:360px;">
                                    @elseif (str_contains($attachment->file_type, 'video'))
                                        <video controls>
                                            <source src="{{ asset('storage/' . $attachment->file_path) }}" type="{{ $attachment->file_type }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                </div>
                            @endforeach
                            <div class="d-flex gap-1 mt-1">
                                <!-- Like Button -->
                                <button class="btn btn-sm text-body react-btn" 
                                        data-id="{{ $post->id }}" 
                                        data-tipe="like">
                                    <i class="fa-{{ $post->userReaction && $post->userReaction->tipe === 'like' ? 'solid' : 'regular' }} fa-thumbs-up"></i>
                                    <span class="like-count">{{ $post->postReactions->where('tipe', 'like')->count() }}</span>
                                </button>

                                <!-- Dislike Button -->
                                <button class="btn btn-sm text-body react-btn" 
                                        data-id="{{ $post->id }}" 
                                        data-tipe="dislike">
                                    <i class="fa-{{ $post->userReaction && $post->userReaction->tipe === 'dislike' ? 'solid' : 'regular' }} fa-thumbs-down"></i>
                                </button>

                                <!-- Comment Button -->
                                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm text-body">
                                    <i class="fa-regular fa-comment"></i>
                                    {{ $post->allRepliesCount() }}
                                </a>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function previewMedia(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview-image');
    const container = document.getElementById('media-preview');

    if (!file) return;

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        container.style.display = 'none'; // You can customize video preview later if needed
    }
}

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".create-modal").forEach((button) => {
        button.addEventListener("click", function() {
            let route = this.dataset.action;
            let name = this.dataset.name;
            let id = this.dataset.id;

            // Clean up any old modals & backdrops first
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            
            fetch(route)
                .then(response => response.text())
                .then(html => {
                    let modalContainer = document.getElementById("modalContainer");
                    modalContainer.innerHTML = '';
                    modalContainer.innerHTML = html;

                    let createModal = "";
                    if (name == 'topik'){
                        createModal = document.getElementById('topicModal');
                        createModal.addEventListener('shown.bs.modal', function () {
                            $('#topicTable').DataTable({
                                "lengthMenu": [5, 10, 20, 40],
                                "order": [[1, "asc"]],
                                "language": {
                                    "emptyTable": "Belum ada topik dalam forum"
                                }
                            });
                        });
                    }
                    new bootstrap.Modal(createModal).show();
                });
        });
    });

    document.querySelectorAll('.react-btn').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            const postId = this.dataset.id;
            const tipe = this.dataset.tipe;
            const buttonEl = this;

            try {
                const res = await fetch(`/posts/${postId}/react`, {
                    method: 'POST', // Ensures it's a POST request
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Laravel CSRF token
                        'Content-Type': 'application/json' // Tell server we're sending JSON
                    },
                    body: JSON.stringify({ tipe }) // Send type data as JSON
                });

                const data = await res.json();

                if (data.success) {
                    // Update UI
                    buttonEl.querySelector('i').classList.remove('fa-regular', 'fa-solid');
                    buttonEl.querySelector('i').classList.add(data.active ? 'fa-solid' : 'fa-regular');

                    // Update counts
                    if(tipe === 'like'){
                        buttonEl.querySelector(`.${tipe}-count`).textContent = data.counts[tipe];
                    }

                    // Reset the opposite button if toggled
                    const sibling = buttonEl.parentElement.querySelector(`.react-btn[data-tipe="${tipe === 'like' ? 'dislike' : 'like'}"]`);
                    if (sibling) {
                        sibling.querySelector('i').classList.remove('fa-solid');
                        sibling.querySelector('i').classList.add('fa-regular');
                    }
                }
            } catch (error) {
                console.error('Reaction failed:', error);
            }
        });
    });
});
</script>
@endsection