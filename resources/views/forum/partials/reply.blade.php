<div class="card mb-3 ms-{{ $level * 5 }}">
    <div class="card-body">
        <small class="text-muted">
            oleh {{ $reply->creator->name }} • {{ $reply->created_at->diffForHumans() }}
        </small>
        <p class="mt-2">{{ $reply->isi }}</p>

        @foreach ($reply->postAttachments as $attachment)
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
            <button class="btn btn-sm text-body react-btn" data-id="{{ $reply->id }}" data-tipe="like">
                <i class="fa-{{ $reply->userReaction && $reply->userReaction->tipe === 'like' ? 'solid' : 'regular' }} fa-thumbs-up"></i>
                <span class="like-count">{{ $reply->postReactions->where('tipe', 'like')->count() }}</span>
            </button>
            <button class="btn btn-sm text-body react-btn" data-id="{{ $reply->id }}" data-tipe="dislike">
                <i class="fa-{{ $reply->userReaction && $reply->userReaction->tipe === 'dislike' ? 'solid' : 'regular' }} fa-thumbs-down"></i>
            </button>
            <a href="{{ route('posts.comment.show', $reply->id) }}" class="btn btn-sm text-body">
                <i class="fa-regular fa-comment"></i>
                {{ $reply->replies->count() }}
            </a>
        </div>

        {{-- Recursive call --}}
        @foreach($reply->replies as $childReply)
            @include('forum.partials.reply', ['reply' => $childReply, 'level' => $level + 1])
        @endforeach
    </div>
</div>