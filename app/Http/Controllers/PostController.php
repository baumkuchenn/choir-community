<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\Post;
use App\Models\PostConcert;
use App\Notifications\KomentarPostNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    public function store(string $slug, Request $request)
    {
        $forum = Forum::where('slug', $slug)
            ->first();
        $request->validate([
            'isi' => 'required|string|max:1000',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,avi,webm|max:10240',
        ]);
        $data = $request->all();
        $data['creator_id'] = Auth::id();
        if ($forum) {
            $data['forums_id'] = $forum->id;
        }

        if (!$request->parent_id) {
            $data['tipe'] = $request->tipe;
        } else {
            $data['tipe'] = 'post';
        };
        
        $post = Post::create($data);
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/posts', $filename, 'public');

                $post->postAttachments()->create([
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                ]);
            }
        }
        if ($data['tipe'] == 'thread' && $request->parent_id) {
            $choir = Auth::user()->members->first()->choir;
            PostConcert::create([
                'posts_id' => $post->id,
                'concerts_id' => $request->concerts_id,
                'choirs_id' => $choir->id,
            ]);
        }
        $message = "";
        if ($data['tipe'] == 'thread') {
            $message = 'Thread berhasil dibuat.';
        } else {
            if ($request->parent_id) {
                $parentPost = Post::find($request->parent_id);
                Notification::send($parentPost->creator, new KomentarPostNotification($post));

                $message = 'Balasan berhasil ditambahkan.';
            } else {
                $message = 'Postingan berhasil dibuat.';
            }
        }
        return redirect()->back()->with('success', $message);
    }

    public function show(string $id, Request $request)
    {
        $post = Post::find($id);
        $replies = $post->replies;
        $forum = Forum::find($post->forums_id);

        $user = Auth::user();
        $followForums = Forum::with('members')
            ->whereHas('members', function ($query) {
                $query->where('users_id', Auth::id());
            })
            ->limit(5)
            ->get();
        $topForums = Forum::withCount('members')
            ->where('visibility', 'public')
            ->orderByDesc('members_count')
            ->limit(5)
            ->get();

        return view('forum.posts.show', compact('post', 'replies', 'forum', 'followForums', 'topForums'));
    }

    public function react(Request $request, Post $post)
    {
        $user = Auth::user();
        $tipe = $request->tipe;

        $reaction = $post->postReactions()->where('users_id', $user->id)->first();

        $active = true;
        if ($reaction && $reaction->tipe === $tipe) {
            $reaction->delete(); // toggle off
            $active = false;
        } else {
            $post->postReactions()->updateOrCreate(
                ['users_id' => $user->id],
                ['tipe' => $tipe]
            );
            $active = true;
        }

        // Send updated counts
        return response()->json([
            'success' => true,
            'active' => $active,
            'counts' => [
                'like' => $post->postReactions()->where('tipe', 'like')->count(),
                'dislike' => $post->postReactions()->where('tipe', 'dislike')->count(),
            ]
        ]);
    }

    public function comment(string $id, Request $request)
    {
        $reply = Post::find($id);
        $post = $reply->reply;
        $replies = $reply->replies;
        $forum = Forum::find($post->forums_id);

        $user = Auth::user();
        $followForums = Forum::with('members')
            ->whereHas('members', function ($query) {
                $query->where('users_id', Auth::id());
            })
            ->limit(5)
            ->get();
        $topForums = Forum::withCount('members')
            ->where('visibility', 'public')
            ->orderByDesc('members_count')
            ->limit(5)
            ->get();

        return view('forum.komentar', compact('post', 'reply', 'replies', 'forum', 'followForums', 'topForums'));
    }
}
