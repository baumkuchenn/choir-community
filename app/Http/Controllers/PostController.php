<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(string $slug, Request $request)
    {
        $forum = Forum::where('slug', $slug)
            ->first();
        $request->validate([
            'isi' => 'required|string|max:45',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,avi,webm|max:10240',
        ]);
        $data = $request->all();
        $data['creator_id'] = Auth::id();
        $data['forums_id'] = $forum->id;
        $data['tipe'] = 'post';

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
        $message = "";
        if ($request->parent_id){
            $message = 'Balasan berhasil ditambahkan.';
        } else {
            $message = 'Postingan berhasil ditambahkan.';
        }
        return redirect()->back()->with('success', $message);
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
        $post = Post::find($id);
        $replies = Post::where('parent_id', $id)->get();
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

        return view('forum.komentar', compact('post', 'replies', 'forum', 'followForums', 'topForums'));
    }
}
