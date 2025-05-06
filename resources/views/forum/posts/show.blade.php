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
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary mb-2">Kembali</a>
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        @if($post->tipe == 'post')
                            <img src="{{ asset('storage/' . $post->forum->foto_profil) }}" alt="Profile" width="32" height="32" class="rounded-circle">
                            <small class="text-muted"><span class="fw-bold">{{ $post->forum->nama }}</span> • {{ $post->created_at->diffForHumans() }}</small>
                        @elseif($post->tipe == 'thread')
                            <img src="{{ asset('storage/' . $post->creator->members->first()->choir->logo) }}" alt="Profile" width="32" height="32" class="rounded-circle">
                            <small class="text-muted"><span class="fw-bold">{{ $post->creator->members->first()->choir->nama }}</span> • {{ $post->created_at->diffForHumans() }}</small>
                        @endif
                    </div>
                    <p class="mb-2">{{ $post->isi }}</p>
                    @foreach ($post->postAttachments as $attachment)
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
            <p class="fw-bold">Komentar</p>
            @if(Auth::user())
                @php
                    $slug = 'noslug';
                    if ($forum){
                        $slug = $forum->slug;
                    }
                @endphp
                <form method="POST" action="{{ route('posts.store', $slug) }}" enctype="multipart/form-data" class="card border-0 shadow-sm mb-3">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $post->id }}">
                    <div class="card-body d-flex">
                        <!-- User Avatar -->
                        <img src="https://github.com/mdo.png" alt="Profile" width="32" height="32" class="rounded-circle">

                        <!-- Post Input Area -->
                        <div class="ps-2 flex-grow-1">
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
            @foreach($replies as $item)
                @include('forum.partials.reply', ['reply' => $item, 'level' => 0])
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