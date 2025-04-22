<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    public function index(string $slug)
    {
        $forum = Forum::with('topics')
            ->where('slug', $slug)
            ->first();
        return view('forum.modal.topic', compact('forum'));
    }

    public function store(string $slug, Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:45',
        ]);
        $forum = Forum::where('slug', $slug)->first();
        $slug = Str::slug($request->nama);

        $originalSlug = $slug;
        $counter = 1;
        while (Forum::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $data = $request->all();
        $data['slug'] = $slug;
        $data['forums_id'] = $forum->id;

        ForumTopic::create($data);
        return redirect()->back()->with('success', 'Topik berhasil ditambahkan.');
    }

    public function destroy(string $slug)
    {
        $topik = ForumTopic::where('slug', $slug)
            ->first();
        $topik->delete();

        return redirect()->back()->with('success', 'Topik berhasil dihapus.');
    }
}
